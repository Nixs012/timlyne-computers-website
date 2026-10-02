<?php
namespace App\Controllers;

use App\Models\Faq;
use App\Models\Setting;

class FaqController {
    public function index() {
        $faqs = Faq::getPublished();
        $settings = Setting::getAll();

        $phone = $settings['business_phone_1'] ?? '';
        $waUrl = Setting::getWhatsAppUrl($settings);

        $title = 'Frequently Asked Questions';
        $bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
        $metaDescription = 'Common questions and answers about Timlyne Computer Solutions.';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $canonicalUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/faq';

        require_once __DIR__ . '/../Views/faq.php';
    }
}
