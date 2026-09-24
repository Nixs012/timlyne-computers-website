<?php
namespace App\Controllers;

use App\Models\Setting;

class HomeController {
    public function index() {
        $settings = Setting::getAll();
        require APP_PATH . '/Views/home.php';
    }
}
