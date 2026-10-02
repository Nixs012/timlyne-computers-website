<?php
namespace App\Controllers;

use App\Models\Gallery;
use App\Models\Setting;

class GalleryController {
    public function index() {
        $gallery = Gallery::getPublishedWithMedia();
        $settings = Setting::getAll();

        $phone = $settings['business_phone_1'] ?? '';
        $waUrl = Setting::getWhatsAppUrl($settings);

        $title = 'Gallery';
        $bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $metaDescription = 'Browse our gallery of work and installations at Timlyne Computer Solutions.';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $canonicalUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/gallery';

        require_once __DIR__ . '/../Views/gallery.php';
    }
}
