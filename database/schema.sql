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
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_audit_logs_created_at (created_at)
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pages_is_published (is_published)
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(100),
    short_description TEXT,
    content LONGTEXT,
    is_published BOOLEAN DEFAULT TRUE,
    INDEX idx_services_is_published (is_published)
);

CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    INDEX idx_faqs_sort_order (sort_order)
);

-- Catalog
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    sort_order INT DEFAULT 0,
    INDEX idx_categories_sort_order (sort_order)
);

CREATE TABLE products (
    id VARCHAR(50) PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    icon VARCHAR(50),
    image_path VARCHAR(255),
    specs JSON,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_products_category (category_id),
    INDEX idx_products_is_published (is_published),
    INDEX idx_products_created_at (created_at)
);

-- Media
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL UNIQUE,
    file_type VARCHAR(50) NOT NULL,
    alt_text VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_media_uploaded_at (uploaded_at)
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_id INT NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE,
    INDEX idx_gallery_is_published (is_published),
    INDEX idx_gallery_sort_order (sort_order)
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_messages_status (status),
    INDEX idx_contact_messages_created_at (created_at)
);

-- Chatbot System
CREATE TABLE chatbot_settings (
    id INT PRIMARY KEY DEFAULT 1,
    welcome_message TEXT,
    fallback_message TEXT,
    is_enabled BOOLEAN DEFAULT TRUE,
    whatsapp_handoff_enabled BOOLEAN DEFAULT TRUE,
    CHECK (id = 1)
);

CREATE TABLE chatbot_conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    customer_questions TEXT,
    chatbot_responses TEXT,
    status ENUM('active', 'ended', 'handoff') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_chatbot_conv_status (status),
    INDEX idx_chatbot_conv_created_at (created_at)
);

-- Settings & SEO
CREATE TABLE redirects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    old_url VARCHAR(255) NOT NULL UNIQUE,
    new_url VARCHAR(255) NOT NULL,
    status_code INT DEFAULT 301,
    INDEX idx_redirects_old_url (old_url)
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
    sort_order INT DEFAULT 0,
    INDEX idx_social_links_sort_order (sort_order)
);
