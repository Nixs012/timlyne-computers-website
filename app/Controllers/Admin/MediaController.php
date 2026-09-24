<?php
namespace App\Controllers\Admin;

use App\Models\Media;
use App\Core\Security;
use App\Core\Session;

class MediaController {
    public function index() {
        $title = "Media Library";
        $media = Media::getAll();
        require APP_PATH . '/Views/admin/media.php';
    }

    public function upload() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Session::set('error', 'Upload failed or no file selected.');
            header('Location: /admin/media');
            exit;
        }

        $file = $_FILES['file'];
        
        // Size validation (e.g., max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::set('error', 'File size exceeds 5MB limit.');
            header('Location: /admin/media');
            exit;
        }

        // MIME Type validation using finfo (reads actual content)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];

        if (!array_key_exists($mime, $allowedMimes)) {
            Session::set('error', 'Invalid file type. Only genuine images are allowed.');
            header('Location: /admin/media');
            exit;
        }

        // Generate safe filename
        $ext = $allowedMimes[$mime];
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalName);
        $fileName = uniqid() . '-' . $safeName . '.' . $ext;

        $uploadDir = ROOT_PATH . '/public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $dbPath = '/uploads/' . $fileName;
            Media::create($dbPath, $mime, $_POST['alt_text'] ?? '');
            Session::set('success', 'Image uploaded successfully.');
        } else {
            Session::set('error', 'Failed to move uploaded file.');
        }

        header('Location: /admin/media');
        exit;
    }

    public function delete() {
        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token.");
        }
        
        $id = $_POST['id'] ?? null;
        if ($id) {
            $item = Media::getById($id);
            if ($item) {
                $filePath = ROOT_PATH . '/public' . $item['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                Media::delete($id);
                Session::set('success', 'Image deleted successfully.');
            }
        }
        
        header('Location: /admin/media');
        exit;
    }
}
