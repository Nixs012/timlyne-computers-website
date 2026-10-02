<?php
namespace App\Controllers;

use App\Models\Setting;
use App\Models\Page;
use App\Models\ChatbotSetting;

class HomeController {
    public function index() {
        $settings = Setting::getAll();
        $chatbotSettings = ChatbotSetting::getChatbotSettings();
        $seoSettings = Setting::getAllSeo();
        $aboutPage = Page::findBySlug('about');
        require APP_PATH . '/Views/home.php';
    }
}
