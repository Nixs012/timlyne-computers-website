<?php
namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Media;
use App\Core\Session;

class ProductController {
    public function index() {
        $products = Product::getAllAdmin();
        require APP_PATH . '/Views/admin/products/index.php';
    }

    public function create() {
        $categories = Category::getAll();
        $media = Media::getAll(); // For image picker
        require APP_PATH . '/Views/admin/products/create.php';
    }

    public function store() {
        $id = $_POST['id'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';
        $icon = $_POST['icon'] ?? '';
        $imagePath = $_POST['image_path'] ?? '';
        
        $specsRaw = $_POST['specs'] ?? ''; // New line separated
        $specsArray = array_filter(array_map('trim', explode("\n", $specsRaw)));
        $specs = json_encode($specsArray);

        $metaTitle = $_POST['meta_title'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if (!$id || !$name || !$slug || !$categoryId) {
            Session::set('error', 'ID, Name, Slug, and Category are required.');
            header('Location: /admin/products/create');
            exit;
        }

        try {
            Product::create($id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished);
            Session::set('success', 'Product created successfully.');
            header('Location: /admin/products');
            exit;
        } catch (\PDOException $e) {
            Session::set('error', 'Database error: ' . $e->getMessage());
            header('Location: /admin/products/create');
            exit;
        }
    }

    public function edit($id) {
        $product = Product::findById($id);
        if (!$product) {
            header('Location: /admin/products');
            exit;
        }
        $categories = Category::getAll();
        $media = Media::getAll(); // For image picker
        require APP_PATH . '/Views/admin/products/edit.php';
    }

    public function update($id) {
        $categoryId = $_POST['category_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';
        $icon = $_POST['icon'] ?? '';
        $imagePath = $_POST['image_path'] ?? '';
        
        $specsRaw = $_POST['specs'] ?? ''; 
        $specsArray = array_filter(array_map('trim', explode("\n", $specsRaw)));
        $specs = json_encode($specsArray);

        $metaTitle = $_POST['meta_title'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        try {
            Product::update($id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished);
            Session::set('success', 'Product updated successfully.');
        } catch (\PDOException $e) {
            Session::set('error', 'Database error: ' . $e->getMessage());
        }
        
        header('Location: /admin/products');
        exit;
    }

    public function delete($id) {
        try {
            Product::delete($id);
            Session::set('success', 'Product deleted successfully.');
        } catch (\PDOException $e) {
            Session::set('error', 'Database error: ' . $e->getMessage());
        }
        
        header('Location: /admin/products');
        exit;
    }
}
