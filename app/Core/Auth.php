<?php
namespace App\Core;

use App\Config\Database;

class Auth {
    public static function login($email, $password) {
        // Simple Throttling
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if (self::isThrottled($ip)) {
            return false;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT a.*, r.name as role_name FROM admins a JOIN roles r ON a.role_id = r.id WHERE a.email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            Session::set('admin_id', $admin['id']);
            Session::set('role_id', $admin['role_id']);
            Session::set('role_name', $admin['role_name']);
            self::clearThrottle($ip);
            return true;
        }

        self::incrementThrottle($ip);
        return false;
    }

    public static function logout() {
        Session::set('admin_id', null);
        session_destroy();
    }

    public static function check() {
        return Session::get('admin_id') !== null;
    }

    public static function hasRole($roleName) {
        return Session::get('role_name') === $roleName;
    }

    private static function isThrottled($ip) {
        $attempts = Session::get("login_attempts_$ip", 0);
        $lastAttempt = Session::get("last_attempt_$ip", 0);
        if ($attempts >= 5 && (time() - $lastAttempt < 300)) {
            return true;
        }
        return false;
    }

    private static function incrementThrottle($ip) {
        $attempts = Session::get("login_attempts_$ip", 0);
        Session::set("login_attempts_$ip", $attempts + 1);
        Session::set("last_attempt_$ip", time());
    }

    private static function clearThrottle($ip) {
        Session::set("login_attempts_$ip", 0);
    }
}
