<?php
namespace App\Core\Middleware;

class AuthMiddleware {
    public function handle() {
        // Stub: In real app, check Session::get('admin_id')
        $isAuthenticated = false; 
        
        if (!$isAuthenticated) {
            header('Location: /admin/login');
            exit;
        }
        return true;
    }
}
