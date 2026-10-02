<?php
namespace App\Controllers;

use App\Models\Setting;
use App\Models\Page;

class HomeController {
    public function index() {
        $settings = Setting::getAll();
        $seoSettings = Setting::getAllSeo();
        $aboutPage = Page::findBySlug('about');
        require APP_PATH . '/Views/home.php';
    }
}
