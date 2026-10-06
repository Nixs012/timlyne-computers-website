<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Security;
use App\Core\Session;

class AuthController {
    public function showLoginForm() {
        if (Auth::check()) {
            header('Location: /admin');
            exit;
        }
        require APP_PATH . '/Views/admin/login.php';
    }

    public function login() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::login($email, $password)) {
            \App\Models\AuditLog::log(Session::get('admin_id'), 'login', 'admin', Session::get('admin_id'), 'Admin login successful');
            header('Location: /admin');
            exit;
        }

        Session::set('error', 'Invalid credentials or too many attempts.');
        header('Location: /admin/login');
        exit;
    }

    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin");
            exit;
        }

        $adminId = Session::get('admin_id');
        Auth::logout();
        \App\Models\AuditLog::log($adminId, 'logout', 'admin', $adminId, 'Admin logout');
        header('Location: /admin/login');
        exit;
    }

    public function showChangePasswordForm() {
        if (!Auth::check()) {
            header('Location: /admin/login');
            exit;
        }
        require APP_PATH . '/Views/admin/change-password.php';
    }

    public function changePassword() {
        if (!Auth::check()) {
            header('Location: /admin/login');
            exit;
        }

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header('Location: /admin/change-password');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate new/confirm match first (user-facing, no info leak)
        if ($newPassword !== $confirmPassword) {
            Session::set('error', 'New passwords do not match.');
            header('Location: /admin/change-password');
            exit;
        }

        $adminId = Session::get('admin_id');

        $result = Auth::changePassword($adminId, $currentPassword, $newPassword);

        if ($result['ok']) {
            Session::set('success', 'Password changed successfully.');
        } else {
            Session::set('error', $result['error']);
        }

        header('Location: /admin/change-password');
        exit;
    }
}
