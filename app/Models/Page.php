<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Page {
    public static function getAll() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM pages ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPublished() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM pages WHERE is_published = 1 ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findBySlug($slug) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($title, $slug, $content, $meta_title = '', $meta_description = '', $is_published = 0) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO pages (title, slug, content, meta_title, meta_description, is_published) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $content, $meta_title, $meta_description, $is_published]);
        return $db->lastInsertId();
    }

    public static function update($id, $title, $slug, $content, $meta_title = '', $meta_description = '', $is_published = 0) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pages SET title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?, is_published = ? WHERE id = ?");
        return $stmt->execute([$title, $slug, $content, $meta_title, $meta_description, $is_published, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM pages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
