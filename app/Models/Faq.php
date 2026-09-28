<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Faq {
    public static function getAll() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPublished() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM faqs WHERE is_published = 1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM faqs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($question, $answer, $sort_order = 0, $is_published = 1) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO faqs (question, answer, sort_order, is_published) VALUES (?, ?, ?, ?)");
        $stmt->execute([$question, $answer, $sort_order, $is_published]);
        return $db->lastInsertId();
    }

    public static function update($id, $question, $answer, $sort_order = 0, $is_published = 1) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE faqs SET question = ?, answer = ?, sort_order = ?, is_published = ? WHERE id = ?");
        return $stmt->execute([$question, $answer, $sort_order, $is_published, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function move($id, $direction) {
        $db = Database::getConnection();
        $faq = self::findById($id);
        if (!$faq) return false;

        $currentOrder = $faq['sort_order'];
        if ($direction === 'up') {
            $stmt = $db->prepare("SELECT id, sort_order FROM faqs WHERE sort_order < ? ORDER BY sort_order DESC, id DESC LIMIT 1");
            $stmt->execute([$currentOrder]);
            $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$neighbor) return false;

            $db->prepare("UPDATE faqs SET sort_order = ? WHERE id = ?")->execute([$neighbor['sort_order'], $id]);
            $db->prepare("UPDATE faqs SET sort_order = ? WHERE id = ?")->execute([$currentOrder, $neighbor['id']]);
        } else {
            $stmt = $db->prepare("SELECT id, sort_order FROM faqs WHERE sort_order > ? ORDER BY sort_order ASC, id ASC LIMIT 1");
            $stmt->execute([$currentOrder]);
            $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$neighbor) return false;

            $db->prepare("UPDATE faqs SET sort_order = ? WHERE id = ?")->execute([$neighbor['sort_order'], $id]);
            $db->prepare("UPDATE faqs SET sort_order = ? WHERE id = ?")->execute([$currentOrder, $neighbor['id']]);
        }
        return true;
    }
}
