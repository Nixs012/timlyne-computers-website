<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Setting;

class ProductPageController {
    public function show($slug) {
        $product = Product::findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            echo "Product not found."; // We could show a 404 view here instead
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

        require APP_PATH . '/Views/product.php';
    }
}
