<?php
namespace App\Controllers\Admin;

class DashboardController {
    public function index() {
        $title = "Dashboard";
        $content = "<h2>Welcome to the Admin Dashboard</h2><p>Overview statistics will appear here.</p>";
        require APP_PATH . '/Views/admin/layout.php';
    }

    public function placeholder() {
        $title = "Placeholder Module";
        $content = "<h2>This module is under construction</h2>";
        require APP_PATH . '/Views/admin/layout.php';
    }
}
