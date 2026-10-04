<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
spl_autoload_register(function ($class) {
    $path = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    $path = str_replace(ROOT_PATH . '/App/', APP_PATH . '/', $path);
    if (file_exists($path)) {
        require_once $path;
    }
});
\App\Core\Env::load(ROOT_PATH . '/.env');

$action = $argv[1] ?? 'set';
if ($action === 'set') {
    \App\Models\Setting::saveSeo(['home_meta_title' => 'TEST NEW SEO TITLE']);
} else {
    \App\Models\Setting::saveSeo(['home_meta_title' => '']);
}
