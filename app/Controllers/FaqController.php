<?php
namespace App\Controllers;

use App\Models\Faq;
use App\Models\Setting;

class FaqController {
    public function index() {
        $faqs = Faq::getPublished();
        $settings = Setting::getAll();

        $phone = $settings['business_phone_1'] ?? '';
        $cleanWaNumber = preg_replace('/[^0-9]/', '', $settings['business_whatsapp'] ?? '');
        $waMsg = $settings['whatsapp_default_message'] ?? '';
        $waUrl = "https://wa.me/{$cleanWaNumber}?text=" . rawurlencode($waMsg);

        $title = 'Frequently Asked Questions';
        $bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $metaDescription = 'Common questions and answers about Timlyne Computer Solutions.';

        require_once __DIR__ . '/../Views/faq.php';
    }
}
