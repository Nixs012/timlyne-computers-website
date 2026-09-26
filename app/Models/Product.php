<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Product {
    public static function getAllAdmin() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }

    public static function getAllPublic() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_published = 1 ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findBySlug($slug) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.is_published = 1");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function create($id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO products (id, category_id, name, slug, price, description, icon, image_path, specs, meta_title, meta_description, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished]);
        return $id;
    }

    public static function update($id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE products SET category_id = ?, name = ?, slug = ?, price = ?, description = ?, icon = ?, image_path = ?, specs = ?, meta_title = ?, meta_description = ?, is_published = ? WHERE id = ?");
        return $stmt->execute([$categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished, $id]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
