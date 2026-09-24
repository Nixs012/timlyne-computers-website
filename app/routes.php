<?php
// Public routes
$router->get('/', 'HomeController@index');

// Admin routes
$router->get('/admin', 'Admin\DashboardController@index', [\App\Core\Middleware\AuthMiddleware::class]);
