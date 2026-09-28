<?php
namespace App\Controllers\Admin;

use App\Models\Faq;
use App\Core\Session;
use App\Core\Security;

class FaqController {
    public function index() {
        $faqs = Faq::getAll();
        require APP_PATH . '/Views/admin/faqs/index.php';
    }

    public function create() {
        require APP_PATH . '/Views/admin/faqs/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/faqs/create");
            exit;
        }

        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            Session::set('error', 'Question and answer are required.');
            header("Location: /admin/faqs/create");
            exit;
        }

        Faq::create($question, $answer, $sort_order, $is_published);
        Session::set('success', 'FAQ created successfully.');
        header("Location: /admin/faqs");
        exit;
    }

    public function edit($id) {
        $faq = Faq::findById($id);
        if (!$faq) {
            Session::set('error', 'FAQ not found.');
            header("Location: /admin/faqs");
            exit;
        }
        require APP_PATH . '/Views/admin/faqs/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/faqs");
            exit;
        }

        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            Session::set('error', 'Question and answer are required.');
            header("Location: /admin/faqs");
            exit;
        }

        Faq::update($id, $question, $answer, $sort_order, $is_published);
        Session::set('success', 'FAQ updated successfully.');
        header("Location: /admin/faqs");
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/faqs");
            exit;
        }
        Faq::delete($id);

        Session::set('success', 'FAQ deleted successfully.');
        header("Location: /admin/faqs");
        exit;
    }

    public function move($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/faqs");
            exit;
        }

        $direction = $_POST['direction'] ?? '';
        if (!in_array($direction, ['up', 'down'])) {
            Session::set('error', 'Invalid move direction.');
            header("Location: /admin/faqs");
            exit;
        }

        if (Faq::move($id, $direction)) {
            Session::set('success', 'FAQ order updated.');
        } else {
            Session::set('error', 'Could not move FAQ.');
        }
        header("Location: /admin/faqs");
        exit;
    }
}
