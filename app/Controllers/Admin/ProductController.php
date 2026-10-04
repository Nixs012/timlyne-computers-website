<?php
namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Media;
use App\Core\Security;
use App\Core\Session;

class ProductController {
    public function index() {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $total = Product::getTotalAdminCount();
        $products = Product::getPaginatedAdmin($page, $perPage);

        $data = [
            'title' => 'Manage Products',
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage
        ];
        extract($data);
        require APP_PATH . '/Views/admin/products/index.php';
    }

    public function create() {
        $categories = Category::getAll();
        $media = Media::getAll(); // For image picker
        require APP_PATH . '/Views/admin/products/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/products/create");
            exit;
        }

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
            $createdId = Product::create($id, $categoryId, $name, $slug, $price, $description, $icon, $imagePath, $specs, $metaTitle, $metaDescription, $isPublished);
            \App\Models\AuditLog::log(Session::get('admin_id'), 'create', 'product', $createdId, $name);
            Session::set('success', 'Product created successfully.');
            header('Location: /admin/products');
            exit;
        } catch (\PDOException $e) {
            error_log('Failed to create product: ' . $e->getMessage());
            Session::set('error', 'Something went wrong saving this product. Please try again.');
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/products");
            exit;
        }

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
            \App\Models\AuditLog::log(Session::get('admin_id'), 'update', 'product', $id, $name);
            Session::set('success', 'Product updated successfully.');
        } catch (\PDOException $e) {
            error_log('Failed to update product: ' . $e->getMessage());
            Session::set('error', 'Something went wrong updating this product. Please try again.');
        }
        
        header('Location: /admin/products');
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/products");
            exit;
        }

        try {
            Product::delete($id);
            \App\Models\AuditLog::log(Session::get('admin_id'), 'delete', 'product', $id, 'Product deleted');
            Session::set('success', 'Product deleted successfully.');
        } catch (\PDOException $e) {
            error_log('Failed to delete product: ' . $e->getMessage());
            Session::set('error', 'Something went wrong deleting this product. Please try again.');
        }
        
        header('Location: /admin/products');
        exit;
    }
}
