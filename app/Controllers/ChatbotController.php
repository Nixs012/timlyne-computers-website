<?php
namespace App\Controllers;

use App\Core\Security;
use App\Core\Session;
use App\Models\ChatbotConversation;
use App\Models\ChatbotEngine;
use App\Models\ChatbotSetting;
use App\Models\Setting;

class ChatbotController {
    public function ask() {
        header('Content-Type: application/json; charset=utf-8');

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid CSRF token.']);
            return;
        }

        $chatbotSettings = ChatbotSetting::getChatbotSettings();
        if (empty($chatbotSettings['is_enabled'])) {
            http_response_code(503);
            echo json_encode(['error' => 'The chatbot is currently disabled.']);
            return;
        }

        $message = is_string($_POST['message'] ?? null) ? trim($_POST['message']) : '';
        if ($message === '') {
            http_response_code(400);
            echo json_encode(['error' => 'A message is required.']);
            return;
        }

        $sessionId = Session::get('chatbot_session_id');
        if (!is_string($sessionId) || $sessionId === '') {
            $sessionId = bin2hex(random_bytes(16));
            Session::set('chatbot_session_id', $sessionId);
        }

        $settings = Setting::getAll();
        $answer = ChatbotEngine::answer($message, $settings);
        ChatbotConversation::logMessage($sessionId, $message, $answer['reply']);

        echo json_encode([
            'reply' => $answer['reply'],
            'matched' => $answer['matched'],
            'handoff' => $answer['handoff'],
            'whatsappUrl' => $answer['handoff'] ? Setting::getWhatsAppUrl($settings, $message) : null,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}