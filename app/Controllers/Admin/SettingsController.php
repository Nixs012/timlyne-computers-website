<?php
namespace App\Controllers\Admin;

use App\Models\Setting;
use App\Core\Security;
use App\Core\Session;

class SettingsController {
    public function index() {
        $title = "Business Settings";
        $settings = Setting::getAll();
        require APP_PATH . '/Views/admin/settings.php';
    }

    public function save() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        $keys = [
            'business_name', 'business_phone_1', 'business_phone_2',
            'business_whatsapp', 'business_email', 'business_address',
            'opening_hours', 'google_maps_url', 'social_facebook',
            'social_instagram', 'social_twitter'
        ];

        foreach ($keys as $key) {
            if (isset($_POST[$key])) {
                Setting::set($key, trim($_POST[$key]));
            }
        }

        Session::set('success', 'Business settings updated successfully.');
        header('Location: /admin/business-settings');
        exit;
    }
}
