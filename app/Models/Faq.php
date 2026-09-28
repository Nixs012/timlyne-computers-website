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
        if ($sort_order == 0) {
            $stmt = $db->query("SELECT MAX(sort_order) FROM faqs");
            $max = $stmt->fetchColumn();
            $sort_order = ($max === null) ? 1 : (int)$max + 1;
        }
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
        $db->beginTransaction();
        try {
            // 1. Resequence all rows to 1..n to ensure no gaps or duplicates
            $stmt = $db->query("SELECT id FROM faqs ORDER BY sort_order ASC, id ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $index => $row) {
                $db->prepare("UPDATE faqs SET sort_order = ? WHERE id = ?")
                   ->execute([$index + 1, $row['id']]);
            }

            // 2. Find current and neighbor
            $faq = self::findById($id);
            if (!$faq) throw new \Exception("Item not found");

            $sortOrder = (int)$faq['sort_order'];
            $operator = ($direction === 'up') ? '<' : '>';
            $orderBy = ($direction === 'up') ? 'DESC' : 'ASC';

            $stmt = $db->prepare("SELECT id, sort_order FROM faqs WHERE sort_order $operator ? ORDER BY sort_order $orderBy, id ASC LIMIT 1");
            $stmt->execute([$sortOrder]);
            $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($neighbor) {
                $stmt = $db->prepare("UPDATE faqs SET sort_order = CASE WHEN id = ? THEN ? WHEN id = ? THEN ? END WHERE id IN (?, ?)");
                $stmt->execute([
                    $id, $neighbor['sort_order'],
                    $neighbor['id'], $sortOrder,
                    $id, $neighbor['id']
                ]);
            }
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
