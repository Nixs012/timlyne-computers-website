<?php
// Public routes
$router->get('/', 'HomeController@index');
$router->get('/faq', 'FaqController@index');
$router->get('/gallery', 'GalleryController@index');
$router->post('/contact/submit', 'ContactController@store');
$router->get('/sitemap.xml', 'SitemapController@index');
$router->get('/products/[slug]', 'ProductPageController@show');
$router->get('/about', 'PageController@redirectAbout');
$router->get('/privacy-policy', 'PageController@show');
$router->get('/terms-conditions', 'PageController@show');

// API Endpoints
$router->get('/api/products', 'Api\ProductController@index');
$router->get('/api/categories', 'Api\CategoryController@index');

// Admin Auth
$router->get('/admin/login', 'Admin\AuthController@showLoginForm');
$router->post('/admin/login', 'Admin\AuthController@login');
$router->post('/admin/logout', 'Admin\AuthController@logout');

// Admin routes (Protected)
$auth = [\App\Core\Middleware\AuthMiddleware::class];
$superAdmin = [\App\Core\Middleware\AuthMiddleware::class, \App\Core\Middleware\SuperAdminMiddleware::class];

$router->get('/admin', 'Admin\DashboardController@index', $auth);
$router->get('/admin/change-password', 'Admin\AuthController@showChangePasswordForm', $auth);
$router->post('/admin/change-password', 'Admin\AuthController@changePassword', $auth);

// Contact Messages Management
$router->get('/admin/messages', 'Admin\ContactController@index', $auth);
$router->get('/admin/messages/[id]', 'Admin\ContactController@show', $auth);
$router->post('/admin/messages/[id]/status', 'Admin\ContactController@updateStatus', $auth);
$router->post('/admin/messages/[id]/delete', 'Admin\ContactController@delete', $auth);

// FAQ Management
$router->get('/admin/faqs', 'Admin\FaqController@index', $auth);
$router->get('/admin/faqs/create', 'Admin\FaqController@create', $auth);
$router->post('/admin/faqs', 'Admin\FaqController@store', $auth);
$router->get('/admin/faqs/[id]/edit', 'Admin\FaqController@edit', $auth);
$router->post('/admin/faqs/[id]/update', 'Admin\FaqController@update', $auth);
$router->post('/admin/faqs/[id]/delete', 'Admin\FaqController@delete', $auth);
$router->post('/admin/faqs/[id]/move', 'Admin\FaqController@move', $auth);

// Gallery Management
$router->get('/admin/gallery', 'Admin\GalleryController@index', $auth);
$router->get('/admin/gallery/create', 'Admin\GalleryController@create', $auth);
$router->post('/admin/gallery', 'Admin\GalleryController@store', $auth);
$router->get('/admin/gallery/[id]/edit', 'Admin\GalleryController@edit', $auth);
$router->post('/admin/gallery/[id]/update', 'Admin\GalleryController@update', $auth);
$router->post('/admin/gallery/[id]/delete', 'Admin\GalleryController@delete', $auth);
$router->post('/admin/gallery/[id]/move', 'Admin\GalleryController@move', $auth);

// Page Management
$router->get('/admin/pages', 'Admin\PageController@index', $auth);
$router->get('/admin/pages/create', 'Admin\PageController@create', $auth);
$router->post('/admin/pages', 'Admin\PageController@store', $auth);
$router->get('/admin/pages/[id]/edit', 'Admin\PageController@edit', $auth);
$router->post('/admin/pages/[id]/update', 'Admin\PageController@update', $auth);
$router->post('/admin/pages/[id]/delete', 'Admin\PageController@delete', $auth);

// Sidebar placeholder routes
$sidebarRoutes = [
    '/admin/content', '/admin/services',
    '/admin/seo', '/admin/whatsapp'
];
$seoRouteIndex = array_search('/admin/seo', $sidebarRoutes, true);
if ($seoRouteIndex !== false) {
    unset($sidebarRoutes[$seoRouteIndex]);
}
foreach ($sidebarRoutes as $route) {
    $router->get($route, 'Admin\DashboardController@placeholder', $auth);
}

$router->get('/admin/chatbot', 'Admin\ChatbotController@index', $auth);
$router->post('/admin/chatbot', 'Admin\ChatbotController@save', $auth);

$router->get('/admin/seo', 'Admin\SeoController@index', $auth);
$router->post('/admin/seo', 'Admin\SeoController@save', $auth);

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
$router->get('/admin/audit-log', 'Admin\AuditLogController@index', $superAdmin);
$router->get('/admin/business-settings', 'Admin\SettingsController@index', $superAdmin);
$router->post('/admin/business-settings', 'Admin\SettingsController@save', $superAdmin);
$router->get('/admin/social-media', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/analytics', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/users', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/security', 'Admin\DashboardController@placeholder', $superAdmin);
$router->get('/admin/system-settings', 'Admin\DashboardController@placeholder', $superAdmin);

// Catch-all for dynamic pages
$router->post('/chatbot/ask', 'ChatbotController@ask');
$router->get('/[slug]', 'PageController@show');
