<?php
namespace App\Controllers\Admin;

use App\Models\Gallery;
use App\Models\Media;
use App\Core\Session;
use App\Core\Security;

class GalleryController {
    public function index() {
        $gallery = Gallery::getAllWithMedia();
        require APP_PATH . '/Views/admin/gallery/index.php';
    }

    public function create() {
        $media = Media::getAll();
        require APP_PATH . '/Views/admin/gallery/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/gallery/create");
            exit;
        }

        $mediaId = $_POST['media_id'] ?? null;
        $caption = $_POST['caption'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (!$mediaId) {
            Session::set('error', 'Please select an image.');
            header("Location: /admin/gallery/create");
            exit;
        }

        $media = Media::getById($mediaId);
        if (!$media || strpos($media['file_type'], 'image/') !== 0) {
            Session::set('error', 'Invalid image selected.');
            header("Location: /admin/gallery/create");
            exit;
        }

        Gallery::create($mediaId, $caption, $is_published);
        Session::set('success', 'Gallery item created successfully.');
        header("Location: /admin/gallery");
        exit;
    }

    public function edit($id) {
        $item = Gallery::findById($id);
        if (!$item) {
            Session::set('error', 'Gallery item not found.');
            header("Location: /admin/gallery");
            exit;
        }
        require APP_PATH . '/Views/admin/gallery/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/gallery");
            exit;
        }

        $caption = $_POST['caption'] ?? '';
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        // Note: Gallery::update in Model currently takes ($id, $mediaId, $caption, $isPublished)
        // The Model signature is: update($id, $mediaId, $caption, $isPublished)
        // But we only want to change caption/status/sort.
        // We need to get the current mediaId first.
        $item = Gallery::findById($id);
        if (!$item) {
            Session::set('error', 'Gallery item not found.');
            header("Location: /admin/gallery");
            exit;
        }

        Gallery::update($id, $item['media_id'], $caption, $is_published);
        // Model update doesn't handle sort_order currently. I should probably fix that in the model
        // but the prompt says only change these files.
        // Wait, Gallery::update doesn't even have sort_order in its signature.

        Session::set('success', 'Gallery item updated successfully.');
        header("Location: /admin/gallery");
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/gallery");
            exit;
        }

        Gallery::delete($id);
        Session::set('success', 'Gallery item deleted successfully.');
        header("Location: /admin/gallery");
        exit;
    }

    public function move($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Invalid CSRF token.');
            header("Location: /admin/gallery");
            exit;
        }

        $direction = $_POST['direction'] ?? '';
        if (!in_array($direction, ['up', 'down'])) {
            Session::set('error', 'Invalid move direction.');
            header("Location: /admin/gallery");
            exit;
        }

        if (Gallery::move($id, $direction)) {
            Session::set('success', 'Gallery order updated.');
        } else {
            Session::set('error', 'Could not move gallery item.');
        }
        header("Location: /admin/gallery");
        exit;
    }
}
