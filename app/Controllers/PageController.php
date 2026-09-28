<?php
namespace App\Controllers;

use App\Models\Page;
use App\Models\Setting;

class PageController {
    public function show($slug = null) {
        if ($slug === null) {
            $slug = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }
        $page = Page::findBySlug($slug);
        
        if (!$page || !$page['is_published']) {
            http_response_code(404);
            $settings = Setting::getAll();
            $title = '404 - Page Not Found';
            require_once __DIR__ . '/../Views/404.php';
            exit;
        }

        $settings = Setting::getAll();
        
        $phone = $settings['business_phone_1'] ?? '';
        $cleanWaNumber = preg_replace('/[^0-9]/', '', $settings['business_whatsapp'] ?? '');
        $waMsg = $settings['whatsapp_default_message'] ?? '';
        $waUrl = "https://wa.me/{$cleanWaNumber}?text=" . rawurlencode($waMsg);
        
        $title = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
        $bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $metaDescription = $page['meta_description'] ?? '';

        require_once __DIR__ . '/../Views/page.php';
    }
}
