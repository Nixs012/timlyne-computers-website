<?php
namespace App\Controllers\Admin;

class DashboardController {
    public function index() {
        require APP_PATH . '/Views/admin/dashboard.php';
    }
}
