<?php
namespace App\Core;

class Security {
    /**
     * Output escaping helper (prevents XSS)
     */
    public static function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * CSRF Token generation
     */
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF Token verification
     */
    public static function verifyCsrfToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Global helper for templates
function e($string) {
    return \App\Core\Security::e($string);
}
