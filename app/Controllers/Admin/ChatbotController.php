<?php
namespace App\Controllers\Admin;

use App\Core\Security;
use App\Core\Session;
use App\Models\ChatbotSetting;

class ChatbotController {
    public function index() {
        $title = 'Chatbot Settings';
        $settings = ChatbotSetting::getChatbotSettings();
        $formData = Session::get('chatbot_form_data');
        if (is_array($formData)) {
            $settings = array_merge($settings, $formData);
            Session::set('chatbot_form_data', null);
        }
        require APP_PATH . '/Views/admin/chatbot.php';
    }

    public function save() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        $settings = [
            'welcome_message' => is_string($_POST['welcome_message'] ?? null) ? trim($_POST['welcome_message']) : '',
            'fallback_message' => is_string($_POST['fallback_message'] ?? null) ? trim($_POST['fallback_message']) : '',
            'is_enabled' => isset($_POST['is_enabled']) ? 1 : 0,
            'whatsapp_handoff_enabled' => isset($_POST['whatsapp_handoff_enabled']) ? 1 : 0,
        ];

        if ($settings['is_enabled'] && ($settings['welcome_message'] === '' || $settings['fallback_message'] === '')) {
            Session::set('error', 'Welcome and fallback messages are required when the chatbot is enabled.');
            Session::set('chatbot_form_data', $settings);
            header('Location: /admin/chatbot');
            exit;
        }

        ChatbotSetting::saveChatbotSettings($settings);
        Session::set('success', 'Chatbot settings updated successfully.');
        header('Location: /admin/chatbot');
        exit;
    }
}