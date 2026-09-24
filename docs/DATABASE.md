# Database Documentation

This document provides a brief explanation of each table in the Timlyne Computers CMS database.

- **roles**: Defines administrator roles (e.g., Super Admin, Editor) to manage access levels.
- **permissions**: Stores granular system permissions that can be assigned to roles.
- **role_permissions**: A junction table that links roles to their respective permissions.
- **admins**: Stores administrator accounts and securely hashes their passwords.
- **audit_logs**: Records important actions performed by administrators for security tracking.
- **pages**: Stores dynamic content for public-facing informational pages (e.g., About Us).
- **services**: Stores the business services offered (e.g., PC Repair, Networking).
- **faqs**: Stores frequently asked questions and their answers for the website and chatbot.
- **categories**: Defines product categories to organize the hardware inventory.
- **products**: Stores the inventory of hardware products, prices, and JSON-encoded specifications.
- **media**: Tracks all uploaded files and images in the system.
- **gallery**: Manages images selected to be displayed in the public-facing gallery section.
- **contact_messages**: Stores inquiries submitted by customers through the public contact form.
- **chatbot_settings**: A single-row table that stores configuration and messages for the AI chatbot.
- **chatbot_conversations**: Logs chatbot interactions with customers for analytics and handoff contexts.
- **redirects**: Maps old URLs to new URLs for SEO preservation (e.g., 301 redirects).
- **seo_settings**: Stores global SEO metadata like default titles, descriptions, and keywords.
- **business_settings**: Stores global contact and location details (e.g., phone, address, email) centrally.
- **social_links**: Stores links to the business's social media profiles for header/footer rendering.
