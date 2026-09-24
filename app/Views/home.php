<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Core\Security::e($title) ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        .placeholder-hero { padding: 5rem 1rem; text-align: center; background: #f8fafc; }
        .placeholder-hero h1 { color: #0f172a; font-size: 2.5rem; margin-bottom: 1rem; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: #2563eb; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="placeholder-hero">
        <h1>Welcome to Timlyne Computers CMS</h1>
        <p>This is the newly scaffolded PHP 8 application powered by our custom routing engine.</p>
        <br>
        <p>CSRF Token generated: <code><?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?></code></p>
        <br>
        <a href="/admin" class="btn">Go to Admin Dashboard</a>
    </div>
</body>
</html>
