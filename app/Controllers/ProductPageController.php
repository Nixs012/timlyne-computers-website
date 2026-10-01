<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Setting;

class ProductPageController {
    public function show($slug) {
        $product = Product::findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            $settings = Setting::getAll();
            $title = '404 - Page Not Found';
            require APP_PATH . '/Views/404.php';
            exit;
        }

        $settings = Setting::getAll();
        
        // Prepare some data for meta tags and whatsapp
        $phone = $settings['business_phone_1'] ?? '';
        $whatsappNumber = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($whatsappNumber, '0')) {
            $whatsappNumber = '254' . substr($whatsappNumber, 1);
        }
        
        $whatsappText = "Hi, I am interested in " . $product['name'] . " listed at Ksh " . number_format($product['price']);
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . rawurlencode($whatsappText);

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $canonicalUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/products/' . $product['slug'];
        $ogImage = !empty($product['image_path'])
            ? (str_starts_with($product['image_path'], 'http') ? $product['image_path'] : $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/' . ltrim($product['image_path'], '/'))
            : null;

        $fallbackDescription = $product['name'] . ' — available at ' . ($settings['business_name'] ?? 'Timlyne Computer Solutions') . ', Mombasa, Kenya.';
        $productDescription = $product['meta_description'] ?: ($product['description'] ?: $fallbackDescription);

        $productJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'],
            'description' => $productDescription,
            'url' => $canonicalUrl,
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'KES',
                'price' => (string) $product['price'],
                'availability' => !empty($product['is_published']) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => $canonicalUrl,
            ],
        ];
        if ($ogImage) { $productJsonLd['image'] = $ogImage; }

        require APP_PATH . '/Views/product.php';
    }
}
