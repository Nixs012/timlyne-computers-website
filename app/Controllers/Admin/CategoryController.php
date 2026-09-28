<?php
namespace App\Controllers\Admin;

use App\Models\Category;
use App\Core\Session;

class CategoryController {
    public function index() {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;

        $total = Category::getTotalCount();
        $categories = Category::getPaginated($page, $perPage);

        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage
        ];
        extract($data);
        require APP_PATH . '/Views/admin/categories.php';
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $sortOrder = $_POST['sort_order'] ?? 0;

        if (!$name || !$slug) {
            Session::set('error', 'Name and Slug are required.');
            header('Location: /admin/categories');
            exit;
        }

        try {
            Category::create($name, $slug, $sortOrder);
            Session::set('success', 'Category created successfully.');
        } catch (\PDOException $e) {
            Session::set('error', 'Database error: ' . $e->getMessage());
        }
        
        header('Location: /admin/categories');
        exit;
    }

    public function update($id) {
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $sortOrder = $_POST['sort_order'] ?? 0;

        try {
            Category::update($id, $name, $slug, $sortOrder);
            Session::set('success', 'Category updated successfully.');
        } catch (\PDOException $e) {
            Session::set('error', 'Database error: ' . $e->getMessage());
        }
        
        header('Location: /admin/categories');
        exit;
    }

    public function delete($id) {
        try {
            Category::delete($id);
            Session::set('success', 'Category deleted successfully.');
        } catch (\PDOException $e) {
            Session::set('error', 'Database error (perhaps products still exist in this category?): ' . $e->getMessage());
        }
        
        header('Location: /admin/categories');
        exit;
    }
}
