-- Insert super admin role
INSERT INTO roles (id, name) VALUES (1, 'Super Admin');

-- Insert placeholder super admin user
INSERT INTO admins (role_id, name, email, password_hash) 
VALUES (1, 'System Administrator', 'admin@timlynecomputers.co.ke', '$2y$10$REPLACE_ME_WITH_REAL_HASH_BEFORE_RUNNING');

-- Insert business settings
INSERT INTO business_settings (setting_key, setting_value) VALUES
('business_name', 'Timlyne Computer Solutions Limited'),
('business_address', 'Mwembe Tayari, Hiltop Plaza, 1st Floor, Shop No. M25, Mombasa, Kenya'),
('business_phone_1', '0724 407 638'),
('business_phone_2', '0707 302 212'),
('business_email', 'info@timlynecomputers.co.ke');

-- Insert default pages
INSERT INTO pages (title, slug, content, is_published) VALUES
('About Us', 'about', '<p>Timlyne Computer Solutions Limited is a full-service computer store in Mombasa, supplying businesses and individuals with quality hardware, accessories, and support.</p><p>From Epson printers and original inks to Tenda and TP-Link networking, Lightwave UPS systems, CCTV installations, and complete desktop & laptop solutions — we stock what you need to stay connected and productive.</p>', 1),
('Privacy Policy', 'privacy-policy', '<p>Your privacy is important to us. This policy explains how we handle your personal data when you visit our website or purchase products from us.</p>', 1),
('Terms & Conditions', 'terms-conditions', '<p>By using this website, you agree to the following terms and conditions regarding our hardware sales and service agreements.</p>', 1);

-- Insert default chatbot settings
INSERT INTO chatbot_settings (id, welcome_message, fallback_message, is_enabled, whatsapp_handoff_enabled) 
VALUES (1, 'Hello! 👋 Welcome to Timlyne Computers. How can I help you today?', 'I am not sure how to answer that. Would you like to speak to a human?', TRUE, TRUE);

-- Insert normalized categories
INSERT INTO categories (name, slug, sort_order) VALUES
('Desktops', 'desktops', 10),
('Laptops', 'laptops', 20),
('Epson Printers', 'epson-printers', 30),
('HP Printers', 'hp-printers', 40),
('Plotting Machines', 'plotting-machines', 50),
('Heat Press Machines', 'heat-press-machines', 60),
('Laminators', 'laminators', 70),
('CCTV', 'cctv', 80),
('Networking', 'networking', 90),
('Power Cables', 'power-cables', 100),
('Laptop Adapters', 'laptop-adapters', 110),
('Storage', 'storage', 120),
('UPS', 'ups', 130),
('Epson Inks', 'epson-inks', 140),
('Toners', 'toners', 150),
('Kyocera/Hp Toner Powders', 'kyocera-hp-toner-powders', 160),
('Routers', 'routers', 170),
('RAM', 'ram', 180),
('Professional Cameras', 'professional-cameras', 190),
('Computer Repair & Maintenance', 'computer-repair-maintenance', 200);
