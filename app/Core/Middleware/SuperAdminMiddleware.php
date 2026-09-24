<?php
namespace App\Core\Middleware;

use App\Core\Auth;

class SuperAdminMiddleware {
    public function handle() {
        if (!Auth::hasRole('Super Admin')) {
            http_response_code(403);
            echo "403 Forbidden - Super Admin access required.";
            exit;
        }
        return true;
    }
}
