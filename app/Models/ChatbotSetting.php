<?php
namespace App\Models;

use App\Config\Database;

class ChatbotSetting {
    public static function getChatbotSettings() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, welcome_message, fallback_message, is_enabled, whatsapp_handoff_enabled FROM chatbot_settings WHERE id = 1");
        $settings = $stmt->fetch();

        return $settings ?: [
            'id' => 1,
            'welcome_message' => '',
            'fallback_message' => '',
            'is_enabled' => 1,
            'whatsapp_handoff_enabled' => 1,
        ];
    }

    public static function saveChatbotSettings($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO chatbot_settings (id, welcome_message, fallback_message, is_enabled, whatsapp_handoff_enabled) VALUES (1, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE welcome_message = VALUES(welcome_message), fallback_message = VALUES(fallback_message), is_enabled = VALUES(is_enabled), whatsapp_handoff_enabled = VALUES(whatsapp_handoff_enabled)");
        return $stmt->execute([
            is_string($data['welcome_message'] ?? null) ? trim($data['welcome_message']) : '',
            is_string($data['fallback_message'] ?? null) ? trim($data['fallback_message']) : '',
            !empty($data['is_enabled']) ? 1 : 0,
            !empty($data['whatsapp_handoff_enabled']) ? 1 : 0,
        ]);
    }
}