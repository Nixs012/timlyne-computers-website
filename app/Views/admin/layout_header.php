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

        /* Baseline styling for every admin form, table and flash message.
           This fixes fields flowing inline instead of stacking, without
           needing to touch each individual view file. */
        .main-content form { display: flex; flex-direction: column; gap: 1.1rem; max-width: 640px; }
        .main-content form label { display: block; font-weight: 600; margin-bottom: 0.35rem; color: #1e293b; }
        .main-content form input[type="text"],
        .main-content form input[type="number"],
        .main-content form input[type="email"],
        .main-content form input[type="password"],
        .main-content form input[type="url"],
        .main-content form textarea,
        .main-content form select {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 0.55rem 0.7rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font: inherit;
            background: white;
        }
        .main-content form textarea { min-height: 160px; resize: vertical; }
        .main-content form .checkbox-row { display: flex; align-items: center; gap: 0.5rem; }
        .main-content form .checkbox-row input[type="checkbox"] { width: auto; }
        .main-content form .actions { display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; }
        .main-content form button,
        .main-content form input[type="submit"] {
            padding: 0.6rem 1.2rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font: inherit;
            cursor: pointer;
        }
        .main-content form button:hover,
        .main-content form input[type="submit"]:hover { background: #1d4ed8; }
        .main-content form a.cancel { color: #64748b; text-decoration: underline; }

        .main-content table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .main-content table th, .main-content table td {
            padding: 0.6rem 0.75rem; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: middle;
        }
        .main-content table img { max-width: 80px; height: auto; display: block; border-radius: 4px; }
        .main-content table a { margin-right: 0.75rem; }

        .flash-success, .flash-error {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 0.9rem 1.1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
        }
        .flash-success { background: #dcfce7; color: #166534; }
        .flash-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Timlyne CMS</h2>
        <nav>
            <a href="/admin">Dashboard</a>
            <a href="/admin/content">Content</a>
            <a href="/admin/pages">Pages</a>
            <a href="/admin/faqs">FAQs</a>
            <a href="/admin/categories">Categories</a>
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
            <a href="/admin/audit-log">Audit Log</a>
            <a href="/admin/system-settings">System Settings</a>
        </nav>
        <a href="/admin/change-password" style="display: block; padding: 0.75rem 1rem; color: #cbd5e1; text-decoration: none; border-top: 1px solid #334155;">Change Password</a>
        <form action="/admin/logout" method="POST" class="logout" style="margin-top: auto;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">
            <button type="submit" style="display: block; width: 100%; padding: 1rem; color: white; background: none; border: none; text-align: center; font: inherit; cursor: pointer;">Logout</button>
        </form>
    </div>
    <div class="main-content">
        <div class="header">
            <h1><?= \App\Core\Security::e($title ?? 'Admin') ?></h1>
            <div>Logged in as <strong><?= \App\Core\Security::e(\App\Core\Session::get('role_name')) ?></strong></div>
        </div>
        <div>
