<?php
// Entry point for the application

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Simple autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to path (e.g., App\Controllers\HomeController -> app/Controllers/HomeController.php)
    $path = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    // Lowercase 'app' directory because the namespace will start with 'App\'
    $path = str_replace(ROOT_PATH . '/App/', APP_PATH . '/', $path);
    if (file_exists($path)) {
        require_once $path;
    }
});

// Load Env
\App\Core\Env::load(ROOT_PATH . '/.env');

// Start Secure Session
\App\Core\Session::start();

// Load Routes
$router = new \App\Core\Router();
require APP_PATH . '/routes.php';

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
