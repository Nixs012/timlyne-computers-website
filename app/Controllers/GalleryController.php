<?php
namespace App\Controllers;

use App\Models\Gallery;
use App\Models\Setting;

class GalleryController {
    public function index() {
        $gallery = Gallery::getPublishedWithMedia();
        $settings = Setting::getAll();

        $phone = $settings['business_phone_1'] ?? '';
        $cleanWaNumber = preg_replace('/[^0-9]/', '', $settings['business_whatsapp'] ?? '');
        $waMsg = $settings['whatsapp_default_message'] ?? '';
        $waUrl = "https://wa.me/{$cleanWaNumber}?text=" . rawurlencode($waMsg);

        $title = 'Gallery';
        $bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $metaDescription = 'Browse our gallery of work and installations at Timlyne Computer Solutions.';

        require_once __DIR__ . '/../Views/gallery.php';
    }
}
