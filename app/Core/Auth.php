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

    /**
     * Change an admin's password.
     *
     * @param int    $adminId         ID of the admin whose password is being changed
     * @param string $currentPassword The current password submitted by the admin
     * @param string $newPassword     The new password to set
     * @return array{ok: bool, error: string|null} Result array
     */
    public static function changePassword(int $adminId, string $currentPassword, string $newPassword): array {
        $db = Database::getConnection();

        // Fetch the admin's current password hash
        $stmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin) {
            return ['ok' => false, 'error' => 'Account not found.'];
        }

        // Verify the current password matches
        if (!password_verify($currentPassword, $admin['password_hash'])) {
            return ['ok' => false, 'error' => 'Current password is incorrect.'];
        }

        // Validate minimum length on the new password
        if (strlen($newPassword) < 8) {
            return ['ok' => false, 'error' => 'New password must be at least 8 characters.'];
        }

        // Validate max length to prevent abuse
        if (strlen($newPassword) > 255) {
            return ['ok' => false, 'error' => 'New password is too long (max 255 characters).'];
        }

        // Hash the new password and update
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $result = $updateStmt->execute([$newHash, $adminId]);

        if (!$result) {
            return ['ok' => false, 'error' => 'Password update failed. Please try again.'];
        }

        // Log the audit event (no passwords logged)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        \App\Models\AuditLog::log($adminId, 'change_password', 'admin', $adminId, 'Password changed by user', $ip);

        return ['ok' => true, 'error' => null];
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
