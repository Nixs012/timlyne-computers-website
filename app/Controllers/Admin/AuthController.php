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
            header('Location: /admin');
            exit;
        }

        Session::set('error', 'Invalid credentials or too many attempts.');
        header('Location: /admin/login');
        exit;
    }

    public function logout() {
        Auth::logout();
        header('Location: /admin/login');
        exit;
    }
}
