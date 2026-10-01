<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Setting;

class SitemapController {
    public function index() {
        $settings = Setting::getAll();
        $products = Product::getAllPublic();

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = "{$protocol}://{$host}";

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $staticPages = [
            '' => 'Homepage',
            '/faq' => 'FAQ',
            '/gallery' => 'Gallery',
            '/privacy-policy' => 'Privacy Policy',
            '/terms-conditions' => 'Terms & Conditions',
        ];

        foreach ($staticPages as $path => $name) {
            echo '<url>';
            echo '<loc>' . htmlspecialchars($baseUrl . $path) . '</loc>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>' . ($path === '' ? '1.0' : '0.8') . '</priority>';
            echo '</url>';
        }

        foreach ($products as $product) {
            echo '<url>';
            echo '<loc>' . htmlspecialchars($baseUrl . '/products/' . $product['slug']) . '</loc>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>';
        }

        echo '</urlset>';
    }
}
