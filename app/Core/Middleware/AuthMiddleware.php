<?php
namespace App\Core\Middleware;

use App\Core\Auth;

class AuthMiddleware {
    public function handle() {
        if (!Auth::check()) {
            header('Location: /admin/login');
            exit;
        }
        return true;
    }
}
