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
        
        // WhatsApp inquiry message: admin-configurable template with
        // {product_name} and {price} placeholders, falling back to the
        // original wording if not set. Number comes from the shared helper
        // (business_whatsapp), not business_phone_1, for consistency with
        // every other WhatsApp link on the site.
        $inquiryTemplate = $settings['product_inquiry_whatsapp_message'] ?? '';
        if (trim($inquiryTemplate) === '') {
            $inquiryTemplate = 'Hi, I am interested in {product_name} listed at Ksh {price}.';
        }
        $whatsappText = str_replace(
            ['{product_name}', '{price}'],
            [$product['name'], number_format($product['price'])],
            $inquiryTemplate
        );
        $whatsappUrl = Setting::getWhatsAppUrl($settings, $whatsappText);

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $canonicalUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/products/' . $product['slug'];
        $normalizedImagePath = \App\Models\Product::normalizeImagePath($product['image_path'] ?? '');
        $ogImage = $normalizedImagePath !== ''
            ? (str_starts_with($normalizedImagePath, 'http') ? $normalizedImagePath : $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $normalizedImagePath)
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
