<?php
namespace App\Models;

use App\Config\Database;

class Setting {
    public static function getAllSeo() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT setting_key, setting_value FROM seo_settings");
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    public static function getSeoWarnings() {
        $db = Database::getConnection();
        $productCondition = "is_published = 1 AND (meta_title IS NULL OR TRIM(meta_title) = '' OR meta_description IS NULL OR TRIM(meta_description) = '')";
        $productCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE $productCondition")->fetchColumn();
        $productDescriptionCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE is_published = 1 AND (meta_description IS NULL OR TRIM(meta_description) = '')")->fetchColumn();
        $products = $db->query("SELECT id, name, slug FROM products WHERE $productCondition ORDER BY name LIMIT 10")->fetchAll();

        $pageCondition = "is_published = 1 AND (meta_title IS NULL OR TRIM(meta_title) = '' OR meta_description IS NULL OR TRIM(meta_description) = '')";
        $pageCount = (int)$db->query("SELECT COUNT(*) FROM pages WHERE $pageCondition")->fetchColumn();
        $pageDescriptionCount = (int)$db->query("SELECT COUNT(*) FROM pages WHERE is_published = 1 AND (meta_description IS NULL OR TRIM(meta_description) = '')")->fetchColumn();
        $pages = $db->query("SELECT id, title FROM pages WHERE $pageCondition ORDER BY title LIMIT 10")->fetchAll();

        $mediaCondition = "alt_text IS NULL OR TRIM(alt_text) = ''";
        $mediaCount = (int)$db->query("SELECT COUNT(*) FROM media WHERE $mediaCondition")->fetchColumn();
        $media = $db->query("SELECT id, file_path FROM media WHERE $mediaCondition ORDER BY uploaded_at DESC LIMIT 10")->fetchAll();

        $seoSettings = self::getAllSeo();
        $homeMetadataIssues = [];
        foreach (['home_meta_title', 'home_meta_description'] as $key) {
            if (trim($seoSettings[$key] ?? '') === '') {
                $homeMetadataIssues[] = $key;
            }
        }

        return [
            'products' => [
                'count' => $productCount,
                'empty_meta_description_count' => $productDescriptionCount,
                'items' => $products,
            ],
            'pages' => [
                'count' => $pageCount,
                'empty_meta_description_count' => $pageDescriptionCount,
                'items' => $pages,
            ],
            'media' => [
                'count' => $mediaCount,
                'empty_alt_text_count' => $mediaCount,
                'items' => $media,
            ],
            'homepage' => [
                'count' => count($homeMetadataIssues),
                'items' => $homeMetadataIssues,
            ],
            'google_search_console_verification_configured' => trim($seoSettings['google_search_console_verification'] ?? '') !== '',
        ];
    }

    public static function saveSeo($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO seo_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        foreach ($data as $key => $value) {
            $value = trim($value);
            $stmt->execute([$key, $value, $value]);
        }
    }

    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT setting_key, setting_value FROM business_settings");
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    public static function get($key, $default = '') {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM business_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public static function set($key, $value) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO business_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    public static function getWhatsAppUrl($settings, $customMessage = null) {
        $bWhatsapp = $settings['business_whatsapp'] ?? '';
        if (empty(trim($bWhatsapp))) {
            $bWhatsapp = '+254724407638';
        }
        
        $waMsg = $customMessage ?? ($settings['whatsapp_default_message'] ?? '');
        if (empty(trim($waMsg))) {
            $waMsg = 'Hello, I would like to know more about your products/services.';
        }

        $cleanWaNumber = preg_replace('/[^0-9]/', '', $bWhatsapp);
        if (str_starts_with($cleanWaNumber, '0')) {
            $cleanWaNumber = '254' . substr($cleanWaNumber, 1);
        }

        return "https://wa.me/{$cleanWaNumber}?text=" . rawurlencode($waMsg);
    }
}
