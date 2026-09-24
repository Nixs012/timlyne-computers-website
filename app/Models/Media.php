<?php
namespace App\Models;

use App\Config\Database;

class Media {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM media ORDER BY uploaded_at DESC");
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM media WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($filePath, $fileType, $altText) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO media (file_path, file_type, alt_text) VALUES (?, ?, ?)");
        $stmt->execute([$filePath, $fileType, $altText]);
        return $db->lastInsertId();
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM media WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
