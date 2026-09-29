<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class ContactMessage {
    public static function getAll($status = null) {
        $db = Database::getConnection();
        if ($status) {
            $stmt = $db->prepare("SELECT * FROM contact_messages WHERE status = ? ORDER BY created_at DESC");
            $stmt->execute([$status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name, $email, $phone, $subject, $message) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        return $db->lastInsertId();
    }

    public static function updateStatus($id, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countByStatus() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'new' => $results['new'] ?? 0,
            'read' => $results['read'] ?? 0,
            'archived' => $results['archived'] ?? 0
        ];
    }
}
