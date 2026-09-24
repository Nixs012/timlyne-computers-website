<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= \App\Core\Security::e($title ?? 'Admin') ?> - Timlyne CMS</title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; overflow: hidden; background: #f8fafc; }
        .sidebar { width: 250px; background: #1e293b; color: white; overflow-y: auto; display: flex; flex-direction: column; }
        .sidebar h2 { padding: 1rem; margin: 0; background: #0f172a; font-size: 1.25rem; }
        .sidebar nav a { display: block; padding: 0.75rem 1rem; color: #cbd5e1; text-decoration: none; border-bottom: 1px solid #334155; }
        .sidebar nav a:hover { background: #334155; color: white; }
        .sidebar .logout { margin-top: auto; background: #ef4444; color: white; text-align: center; }
        .main-content { flex: 1; padding: 2rem; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 2rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Timlyne CMS</h2>
        <nav>
            <a href="/admin">Dashboard</a>
            <a href="/admin/content">Content</a>
            <a href="/admin/pages">Pages</a>
            <a href="/admin/products">Products</a>
            <a href="/admin/services">Services</a>
            <a href="/admin/gallery">Gallery</a>
            <a href="/admin/media">Media Library</a>
            <a href="/admin/messages">Messages</a>
            <a href="/admin/chatbot">Chatbot</a>
            <a href="/admin/seo">SEO</a>
            <a href="/admin/whatsapp">WhatsApp</a>
            <a href="/admin/business-settings">Business Settings</a>
            <a href="/admin/social-media">Social Media</a>
            <a href="/admin/analytics">Analytics</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/security">Security</a>
            <a href="/admin/system-settings">System Settings</a>
        </nav>
        <a href="/admin/logout" class="logout" style="padding: 1rem; text-decoration: none;">Logout</a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1><?= \App\Core\Security::e($title ?? 'Admin') ?></h1>
            <div>Logged in as <strong><?= \App\Core\Security::e(\App\Core\Session::get('role_name')) ?></strong></div>
        </div>
        <div>
            <?= $content ?? '' ?>
        </div>
    </div>
</body>
</html>
