<?php
namespace App\Models;

use App\Config\Database;

class Gallery {
    public static function getAllWithMedia() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT g.*, m.file_path as image_url, m.alt_text
            FROM gallery g
            JOIN media m ON g.media_id = m.id
            ORDER BY g.sort_order ASC, g.id ASC
        ");
        return $stmt->fetchAll();
    }

    public static function getPublishedWithMedia() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT g.*, m.file_path as image_url, m.alt_text
            FROM gallery g
            JOIN media m ON g.media_id = m.id
            WHERE g.is_published = 1
            ORDER BY g.sort_order ASC, g.id ASC
        ");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT g.*, m.file_path as image_url, m.alt_text, m.file_type
            FROM gallery g
            JOIN media m ON g.media_id = m.id
            WHERE g.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($mediaId, $caption, $isPublished = 1, $sortOrder = 0) {
        $db = Database::getConnection();
        if ($sortOrder == 0) {
            $stmt = $db->query("SELECT MAX(sort_order) FROM gallery");
            $max = $stmt->fetchColumn();
            $sortOrder = ($max === null) ? 1 : (int)$max + 1;
        }
        $stmt = $db->prepare("INSERT INTO gallery (media_id, caption, is_published, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$mediaId, $caption, $isPublished, $sortOrder]);
        return $db->lastInsertId();
    }

    public static function update($id, $mediaId, $caption, $isPublished) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE gallery SET media_id = ?, caption = ?, is_published = ? WHERE id = ?");
        return $stmt->execute([$mediaId, $caption, $isPublished, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function move($id, $direction) {
        $db = Database::getConnection();
        $db->beginTransaction();
        try {
            // 1. Resequence all rows to 1..n to ensure no gaps or duplicates
            $stmt = $db->query("SELECT id FROM gallery ORDER BY sort_order ASC, id ASC");
            $rows = $stmt->fetchAll();
            foreach ($rows as $index => $row) {
                $db->prepare("UPDATE gallery SET sort_order = ? WHERE id = ?")
                   ->execute([$index + 1, $row['id']]);
            }

            // 2. Find current and neighbor
            $current = self::findById($id);
            if (!$current) throw new \Exception("Item not found");

            $sortOrder = (int)$current['sort_order'];
            $operator = ($direction === 'up') ? '<' : '>';
            $orderBy = ($direction === 'up') ? 'DESC' : 'ASC';

            $stmt = $db->prepare("SELECT id, sort_order FROM gallery WHERE sort_order $operator ? ORDER BY sort_order $orderBy, id ASC LIMIT 1");
            $stmt->execute([$sortOrder]);
            $neighbor = $stmt->fetch();

            if ($neighbor) {
                $stmt = $db->prepare("UPDATE gallery SET sort_order = CASE WHEN id = ? THEN ? WHEN id = ? THEN ? END WHERE id IN (?, ?)");
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
