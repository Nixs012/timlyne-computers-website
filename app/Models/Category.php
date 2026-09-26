<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Category {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($name, $slug, $sortOrder = 0) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug, sort_order) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $sortOrder]);
        return $db->lastInsertId();
    }

    public static function update($id, $name, $slug, $sortOrder) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ?, sort_order = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $sortOrder, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
