<?php
namespace App\Controllers;

use App\Models\ContactMessage;
use App\Core\Session;
use App\Core\Security;

class ContactController {
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        // Honeypot check
        if (!empty($_POST['website'])) {
            Session::set('success', 'Your message has been sent successfully.');
            header('Location: /#contact');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            Session::set('error', 'Name, email, and message are required.');
            Session::set('contact_form', $_POST);
            header('Location: /#contact');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::set('error', 'Please provide a valid email address.');
            Session::set('contact_form', $_POST);
            header('Location: /#contact');
            exit;
        }

        ContactMessage::create($name, $email, $phone, $subject, $message);
        Session::set('success', 'Thank you! Your message has been sent successfully.');
        header('Location: /#contact');
        exit;
    }
}
