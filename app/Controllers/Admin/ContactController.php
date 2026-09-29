<?php
namespace App\Controllers\Admin;

use App\Models\ContactMessage;
use App\Core\Session;
use App\Core\Security;

class ContactController {
    public function index() {
        $status = $_GET['status'] ?? null;
        $messages = ContactMessage::getAll($status);
        $counts = ContactMessage::countByStatus();
        require APP_PATH . '/Views/admin/messages/index.php';
    }

    public function show($id) {
        $message = ContactMessage::findById($id);
        if (!$message) {
            Session::set('error', 'Message not found.');
            header('Location: /admin/messages');
            exit;
        }

        if ($message['status'] === 'new') {
            ContactMessage::updateStatus($id, 'read');
        }

        require APP_PATH . '/Views/admin/messages/show.php';
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header('Location: /admin/messages');
            exit;
        }

        $status = $_POST['status'] ?? 'read';
        if (!in_array($status, ['new', 'read', 'archived'])) {
            Session::set('error', 'Invalid status.');
            header('Location: /admin/messages');
            exit;
        }

        ContactMessage::updateStatus($id, $status);
        Session::set('success', 'Message status updated.');
        header('Location: /admin/messages');
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header('Location: /admin/messages');
            exit;
        }

        ContactMessage::delete($id);
        Session::set('success', 'Message deleted.');
        header('Location: /admin/messages');
        exit;
    }
}
