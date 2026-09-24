<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $title = "Timlyne Computers | Modern CMS Placeholder";
        require APP_PATH . '/Views/home.php';
    }
}
