<?php
namespace App\Controllers\Admin;

use App\Models\Page;
use App\Core\Session;
use App\Core\Security;

class PageController {
    public function index() {
        $pages = Page::getAll();
        require APP_PATH . '/Views/admin/pages/index.php';
    }

    public function create() {
        require APP_PATH . '/Views/admin/pages/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/pages/create");
            exit;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $content = $_POST['content'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($title) || empty($slug)) {
            Session::set('error', 'Title and slug are required.');
            header("Location: /admin/pages/create");
            exit;
        }

        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            Session::set('error', 'Slug must only contain lowercase letters, numbers, and hyphens.');
            header("Location: /admin/pages/create");
            exit;
        }

        $reserved = ['admin', 'api', 'products', 'assets', 'css', 'js', 'uploads'];
        if (in_array($slug, $reserved)) {
            Session::set('error', 'This slug is reserved and cannot be used.');
            header("Location: /admin/pages/create");
            exit;
        }

        Page::create($title, $slug, $content, $meta_title, $meta_description, $is_published);
        Session::set('success', 'Page created successfully.');
        header("Location: /admin/pages");
        exit;
    }

    public function edit($id) {
        $page = Page::findById($id);
        if (!$page) {
            Session::set('error', 'Page not found.');
            header("Location: /admin/pages");
            exit;
        }
        require APP_PATH . '/Views/admin/pages/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/pages");
            exit;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $content = $_POST['content'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($title) || empty($slug)) {
            Session::set('error', 'Title and slug are required.');
            header("Location: /admin/pages");
            exit;
        }

        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            Session::set('error', 'Slug must only contain lowercase letters, numbers, and hyphens.');
            header("Location: /admin/pages");
            exit;
        }

        $reserved = ['admin', 'api', 'products', 'assets', 'css', 'js', 'uploads'];
        if (in_array($slug, $reserved)) {
            Session::set('error', 'This slug is reserved and cannot be used.');
            header("Location: /admin/pages");
            exit;
        }

        Page::update($id, $title, $slug, $content, $meta_title, $meta_description, $is_published);
        Session::set('success', 'Page updated successfully.');
        header("Location: /admin/pages");
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/pages");
            exit;
        }
        Page::delete($id);

        Session::set('success', 'Page deleted successfully.');
        header("Location: /admin/pages");
        exit;
    }
}
