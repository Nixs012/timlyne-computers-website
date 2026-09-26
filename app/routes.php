<?php
// Public routes
$router->get('/', 'HomeController@index');
$router->get('/products/[slug]', 'ProductPageController@show');

// API Endpoints
$router->get('/api/products', 'Api\ProductController@index');
$router->get('/api/categories', 'Api\CategoryController@index');

// Admin Auth
$router->get('/admin/login', 'Admin\AuthController@showLoginForm');
$router->post('/admin/login', 'Admin\AuthController@login');
$router->get('/admin/logout', 'Admin\AuthController@logout');

// Admin routes (Protected)
$auth = [\App\Core\Middleware\AuthMiddleware::class];
$superAdmin = [\App\Core\Middleware\AuthMiddleware::class, \App\Core\Middleware\SuperAdminMiddleware::class];

$router->get('/admin', 'Admin\DashboardController@index', $auth);

// Sidebar placeholder routes
$sidebarRoutes = [
    '/admin/content', '/admin/pages', '/admin/services',
    '/admin/gallery', '/admin/messages', '/admin/chatbot',
    '/admin/seo', '/admin/whatsapp'
];
foreach ($sidebarRoutes as $route) {
    $router->get($route, 'Admin\DashboardController@placeholder', $auth);
}

// Media Library routes (Editor & Super Admin)
$router->get('/admin/media', 'Admin\MediaController@index', $auth);
$router->post('/admin/media/upload', 'Admin\MediaController@upload', $auth);
$router->post('/admin/media/delete', 'Admin\MediaController@delete', $auth);

// Admin Categories
$router->get('/admin/categories', 'Admin\CategoryController@index', $auth);
$router->post('/admin/categories', 'Admin\CategoryController@store', $auth);
$router->post('/admin/categories/[id]/update', 'Admin\CategoryController@update', $auth);
$router->post('/admin/categories/[id]/delete', 'Admin\CategoryController@delete', $auth);

// Admin Products
$router->get('/admin/products', 'Admin\ProductController@index', $auth);
$router->get('/admin/products/create', 'Admin\ProductController@create', $auth);
$router->post('/admin/products', 'Admin\ProductController@store', $auth);
$router->get('/admin/products/[id]/edit', 'Admin\ProductController@edit', $auth);
$router->post('/admin/products/[id]/update', 'Admin\ProductController@update', $auth);
$router->post('/admin/products/[id]/delete', 'Admin\ProductController@delete', $auth);

// Super Admin only routes
$router->get('/admin/business-settings', 'Admin\SettingsController@index', $superAdmin);
$router->post('/admin/business-settings', 'Admin\SettingsController@save', $superAdmin);
$router->get('/admin/social-media', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/analytics', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/users', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/security', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/system-settings', 'Admin\DashboardController@placeholder', $superAdmin);
