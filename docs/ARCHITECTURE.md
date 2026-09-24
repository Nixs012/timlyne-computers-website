# Phase 1: Architecture Plan

This document outlines the architecture, database schema, routing strategy, and build phases for the Timlyne Computers CMS rebuild based on `docs/SPEC.md` and legacy system constraints.

## 1. Proposed Folder Structure (cPanel Compatible)

In a typical shared cPanel hosting environment, the document root for the main domain is `public_html/`. To ensure security and prevent source code or configuration files from being exposed to the web, the core application will be kept **one level above** `public_html/`.

```text
/home/cpanel_user/
├── app/                  # Application core (not web-accessible)
│   ├── Config/           # Environment variables, database config
│   ├── Core/             # Router, Database connection, Session manager, Security
│   ├── Controllers/      # Application controllers (Admin, Public)
│   ├── Models/           # Data mappers/entities
│   └── Views/            # HTML templates (e.g., using native PHP templating)
├── vendor/               # Composer dependencies (if any)
└── public_html/          # Web-accessible document root
    ├── .htaccess         # Rewrites all requests to index.php
    ├── index.php         # Front controller
    ├── assets/           # CSS, JS, Fonts, public images
    └── uploads/          # User-uploaded media (must prevent script execution)
```

## 2. Full MySQL Schema (Normalized)

The database schema accommodates all 18 entities identified in Section 39 of `SPEC.md`.

```sql
-- Security / RBAC
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    action VARCHAR(255) NOT NULL,
    entity VARCHAR(100) NOT NULL,
    entity_id VARCHAR(50) NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);

-- Content Management
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    is_published BOOLEAN DEFAULT FALSE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(100),
    short_description TEXT,
    content LONGTEXT,
    is_published BOOLEAN DEFAULT TRUE
);

CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0
);

-- Catalog
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    sort_order INT DEFAULT 0
);

CREATE TABLE products (
    id VARCHAR(50) PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    icon VARCHAR(50),
    image_path VARCHAR(255),
    specs JSON, -- For dynamic lists like ["8GB RAM", "256 SSD"]
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Media
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL UNIQUE,
    file_type VARCHAR(50) NOT NULL,
    alt_text VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_id INT NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
);

-- Interactions & Communications
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Chatbot System
CREATE TABLE chatbot_settings (
    id INT PRIMARY KEY DEFAULT 1,
    welcome_message TEXT,
    fallback_message TEXT,
    is_enabled BOOLEAN DEFAULT TRUE,
    whatsapp_handoff_enabled BOOLEAN DEFAULT TRUE,
    CHECK (id = 1) -- Single row table
);

CREATE TABLE chatbot_conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    customer_questions TEXT,
    chatbot_responses TEXT,
    status ENUM('active', 'ended', 'handoff') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings & SEO
CREATE TABLE redirects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    old_url VARCHAR(255) NOT NULL UNIQUE,
    new_url VARCHAR(255) NOT NULL,
    status_code INT DEFAULT 301
);

CREATE TABLE seo_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);

CREATE TABLE business_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);

CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0
);
```

*(Note: Appropriate indexes should be created on `slug`, `category_id`, `status` fields, and `created_at` dates where necessary, which are natively supported by the PK/UNIQUE/FK constraints above, but we will explicitly index them in the schema migration files).*

## 3. Routing Plan

A front-controller pattern (`public_html/index.php`) will handle all traffic via URL rewriting. We will use a fast regex-based PHP router (e.g., AltoRouter or a custom equivalent) mapping URLs to controllers.

### Public Routes

- `GET /` -> `HomeController@index`
- `GET /services` -> `ServiceController@index`
- `GET /services/[slug]` -> `ServiceController@show`
- `GET /products` -> `ProductController@index`
- `GET /products/[slug]` -> `ProductController@show`
- `GET /contact` -> `ContactController@index`
- `POST /contact` -> `ContactController@submit`
- `GET /[slug]` -> `PageController@show` (Dynamic pages like About, FAQ, etc.)
- `POST /api/chat` -> `ChatbotController@handleMessage`

### Protected Admin Routes (requires active Session & RBAC check)

- `GET /admin` -> `Admin\DashboardController@index`
- `GET|POST /admin/login` -> `Admin\AuthController@login`
- `GET /admin/logout` -> `Admin\AuthController@logout`
- CRUD Routes for entities:
  - `GET|POST /admin/products`, `/admin/products/create`, `/admin/products/[id]/edit`, `POST /admin/products/[id]/delete`
  - (Same pattern for Categories, Services, Pages, Gallery, Faqs, Users/Roles)
- Settings & Logs:
  - `GET|POST /admin/settings` -> `Admin\SettingsController@index`
  - `GET /admin/messages` -> `Admin\MessageController@index`

*Security Note:* Sessions will use PHP's `session_set_cookie_params()` with `Secure`, `HttpOnly`, and `SameSite=Lax`.

## 4. Migration Plan for Legacy Data (`legacy/products.js`)

### Step 1: Normalize Categories

The hard-coded list contains **inconsistencies** with the actual assigned product categories:

- 🚨 `All` is a filter, not a category. (Skip insertion)
- 🚨 `Plotting Machines` is listed, but the product uses `category: "Plotter"`. (Unify to `Plotting Machines`)
- 🚨 `Heat Press Machines` is listed, but the product uses `category: "8-in-ONE Heatrpess"`. (Unify to `Heat Press Machines`)
- 🚨 `CCTV'S` is listed, but products use `category: "CCTV"`. (Unify to `CCTV`)
- 🚨 `Cables` is listed, but products use `category: "Power Cables"`. (Unify to `Power Cables`)
- 🚨 `RAMS` is listed, but products use `category: "RAM"`. (Unify to `RAM`)

### Step 2: Migrate Products

- 🚨 **Duplicate ID:** `lap-001` is used for *both* "HP EliteDesk 800 G5 Desktop" and "HP 15s-fq5000 Laptop". We will generate unique slug-based IDs (e.g. `dsk-hp-elitedesk-800-g5` and `lap-hp-15s`) instead of keeping the legacy IDs, or assign `lap-004` to the laptop.
- 🚨 **Malformed IDs:** `"Plotting Machine-001"` and `"Heatpress Machine-002"` have spaces. We will convert them to slug-style IDs.
- Arrays in `specs` will be inserted as JSON strings into the `specs` column.

## 5. Phased Build Order

1. **Phase 1 (Current):** Discovery & Architecture Plan
2. **Phase 2:** Scaffolding & Core System Setup (Folder structure, Database setup, Routing core, Settings/Helpers).
3. **Phase 3:** Auth & Admin Dashboard (Login, Sessions, RBAC, Admin UI shell).
4. **Phase 4:** Core Content Management (Categories, Products CRUD, Legacy Data Migration Script).
5. **Phase 5:** Remaining CMS Features (Pages, Services, Gallery, FAQ, Settings, Messages).
6. **Phase 6:** Public Frontend Integration (Templates, SEO rendering, Contact Form, connecting real DB to public views).
7. **Phase 7:** Chatbot Integration (API endpoint, Settings panel, WhatsApp Handoff logic).
8. **Phase 8:** Final Polish & Testing (Security audit, Upload paths verification, Performance optimization).
