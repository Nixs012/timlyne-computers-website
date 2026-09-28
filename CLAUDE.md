# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Stack
- **Language:** PHP (Native)
- **Database:** MySQL
- **Architecture:** MVC (Model-View-Controller) with a Front-Controller pattern.
- **Environment:** Designed for cPanel shared hosting.

## High-Level Architecture
- **Document Root:** The web-accessible root is `public/` (mapping to `public_html` in production).
- **Application Core:** Located in `app/`, kept outside the web root for security.
  - `app/Core/`: Contains the routing engine (`Router.php`), security, session management, and environment handling.
  - `app/Controllers/`: Handles request logic. Separated into `Admin` (protected) and public controllers.
  - `app/Models/`: Data mapping and database interactions for entities like `Product`, `Category`, and `Page`.
  - `app/Views/`: PHP-based templates for rendering HTML.
- **Routing:** All requests are routed through `public/index.php` using a regex-based router in `app/Core/Router.php`.
- **Database:** Normalized MySQL schema covering RBAC (roles/permissions), Content Management (pages/services), and Product Catalog.

## Common Development Tasks
- **Database Migrations:** Database scripts are located in `database/`. Use `database/schema.sql` for initial setup and `database/seed.sql` for initial data.
- **Adding New Pages/Content:** 
  1. Create/Update the `Page` model in `app/Models/Page.php`.
  2. Add routes in `app/routes.php`.
  3. Implement logic in `app/Controllers/PageController.php`.
  4. Create the corresponding view in `app/Views/`.
- **Admin Features:** Admin controllers are located in `app/Controllers/Admin/` and views in `app/Views/admin/`. Access is protected by `AuthMiddleware` and `SuperAdminMiddleware`.
