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
}
