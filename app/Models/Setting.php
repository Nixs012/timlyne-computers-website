<?php
namespace App\Models;

use App\Config\Database;

class Setting {
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
