<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class ChatbotConversation {
    public static function logMessage($sessionId, $question, $response) {
        $db = Database::getConnection();
        $select = $db->prepare("SELECT id, customer_questions, chatbot_responses FROM chatbot_conversations WHERE session_id = ? ORDER BY id DESC LIMIT 1");
        $select->execute([$sessionId]);
        $conversation = $select->fetch(PDO::FETCH_ASSOC);

        $questions = $conversation ? json_decode($conversation['customer_questions'] ?? '[]', true) : [];
        $responses = $conversation ? json_decode($conversation['chatbot_responses'] ?? '[]', true) : [];
        if (!is_array($questions)) {
            $questions = [];
        }
        if (!is_array($responses)) {
            $responses = [];
        }
        $questions[] = $question;
        $responses[] = $response;

        if ($conversation) {
            $update = $db->prepare("UPDATE chatbot_conversations SET customer_questions = ?, chatbot_responses = ? WHERE id = ?");
            return $update->execute([json_encode($questions, JSON_UNESCAPED_UNICODE), json_encode($responses, JSON_UNESCAPED_UNICODE), $conversation['id']]);
        }

        $insert = $db->prepare("INSERT INTO chatbot_conversations (session_id, customer_questions, chatbot_responses) VALUES (?, ?, ?)");
        return $insert->execute([$sessionId, json_encode($questions, JSON_UNESCAPED_UNICODE), json_encode($responses, JSON_UNESCAPED_UNICODE)]);
    }
}