<?php
$content = ob_start();
?>
<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem; }
    .media-item { border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem; background: white; text-align: center; }
    .media-item img { max-width: 100%; height: 150px; object-fit: contain; background: #f8fafc; border-radius: 4px; }
    .media-item .meta { font-size: 0.8rem; margin: 0.5rem 0; color: #475569; word-break: break-all; }
    .upload-box { background: white; padding: 1.5rem; border: 2px dashed #cbd5e1; border-radius: 8px; margin-bottom: 1rem; }
    .btn { padding: 0.5rem 1rem; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .btn-danger { background: #ef4444; }
    .alert-success { background: #dcfce7; color: #166534; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
    .alert-error { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
</style>

<h2>Media Library</h2>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="alert-success"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<?php if ($msg = \App\Core\Session::get('error')): ?>
    <div class="alert-error"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('error', null); ?>
<?php endif; ?>

<div class="upload-box">
    <h3>Upload New Image</h3>
    <form action="/admin/media/upload" method="POST" enctype="multipart/form-data" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">
        <div>
            <label>Select Image (Max 5MB)</label><br>
            <input type="file" name="file" accept="image/jpeg,image/png,image/webp,image/gif" required>
        </div>
        <div>
            <label>Alt Text / Title</label><br>
            <input type="text" name="alt_text" placeholder="Description for SEO" required>
        </div>
        <button type="submit" class="btn">Upload</button>
    </form>
</div>

<div class="media-grid">
    <?php foreach ($media as $item): ?>
        <div class="media-item">
            <img src="<?= \App\Core\Security::e($item['file_path']) ?>" alt="<?= \App\Core\Security::e($item['alt_text']) ?>">
            <div class="meta">
                <strong><?= \App\Core\Security::e(basename($item['file_path'])) ?></strong><br>
                <?= \App\Core\Security::e($item['alt_text']) ?>
            </div>
            <form action="/admin/media/delete" method="POST" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" class="btn btn-danger" style="width: 100%;">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
    <?php if(empty($media)): ?>
        <p>No images uploaded yet.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/admin/layout.php';
