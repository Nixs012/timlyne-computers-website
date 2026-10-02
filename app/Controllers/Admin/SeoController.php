<?php
namespace App\Controllers\Admin;

use App\Core\Security;
use App\Core\Session;
use App\Models\Setting;

class SeoController {
    public function index() {
        $title = 'SEO Settings';
        $settings = Setting::getAllSeo();
        $seoWarnings = Setting::getSeoWarnings();
        $businessSettings = Setting::getAll();
        $businessName = $businessSettings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $fallbacks = [
            'home_meta_title' => $businessName . ' | Mombasa',
            'home_meta_description' => $businessName . ' — Desktops, Laptops, Printers, CCTV, Networking & more in Mombasa, Kenya.',
            'faq_meta_title' => 'Frequently Asked Questions | ' . $businessName,
            'faq_meta_description' => 'Common questions and answers about Timlyne Computer Solutions.',
            'gallery_meta_title' => 'Gallery | ' . $businessName,
            'gallery_meta_description' => 'Browse our gallery of work and installations at Timlyne Computer Solutions.',
        ];
        $robotsExists = is_file(ROOT_PATH . '/public/robots.txt');
        $sitemapStatus = null;
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (preg_match('/\A[a-z0-9.-]+(?::[0-9]{1,5})?\z/i', $host)) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $context = stream_context_create(['http' => ['timeout' => 3, 'ignore_errors' => true]]);
            $http_response_header = [];
            @file_get_contents($scheme . '://' . $host . '/sitemap.xml', false, $context);
            $statusLine = $http_response_header ? end($http_response_header) : '';
            if (preg_match('/\s([0-9]{3})(?:\s|$)/', $statusLine, $statusMatch)) {
                $sitemapStatus = (int)$statusMatch[1];
            }
        }
        require APP_PATH . '/Views/admin/seo.php';
    }

    public function save() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        $keys = [
            'home_meta_title', 'home_meta_description',
            'faq_meta_title', 'faq_meta_description',
            'gallery_meta_title', 'gallery_meta_description',
            'google_search_console_verification',
        ];
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
        }

        Setting::saveSeo($data);
        Session::set('success', 'SEO settings updated successfully.');
        header('Location: /admin/seo');
        exit;
    }
}