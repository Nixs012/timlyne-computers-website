<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Add Gallery Image</h2>
</div>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div style="background: #10b981; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e($msg) ?>
    </div>
    <?php \App\Core\Session::clear('success'); ?>
<?php endif; ?>
<?php if ($msg = \App\Core\Session::get('error')): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e($msg) ?>
    </div>
    <?php \App\Core\Session::clear('error'); ?>
<?php endif; ?>

<div class="card">
    <form action="/admin/gallery" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">

        <div class="form-group">
            <label>Select Image from Media Library</label>
            <?php if (!empty($media)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; margin-top: 0.5rem; max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0; padding: 1rem; border-radius: 4px; background: #f8fafc;">
                    <?php foreach ($media as $m): ?>
                    <label style="cursor: pointer; border: 1px solid #ddd; padding: 4px; border-radius: 4px; background: white; text-align: center; transition: all 0.2s;">
                        <input type="radio" name="media_id" value="<?= $m['id'] ?>" required style="display: none;">
                        <img src="<?= \App\Core\Security::e($m['file_path']) ?>" alt="<?= \App\Core\Security::e($m['alt_text']) ?>" style="width: 100%; height: 100px; object-fit: cover; border-radius: 2px;">
                        <div style="font-size: 0.75rem; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #64748b;">
                            <?= \App\Core\Security::e($m['file_path']) ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
                <style>
                    input[type="radio"]:checked + img,
                    label:has(input[type="radio"]:checked) {
                        border-color: #3b82f6 !important;
                        background: #eff6ff !important;
                        box-shadow: 0 0 0 2px #3b82f6;
                    }
                </style>
            <?php else: ?>
                <div style="padding: 2rem; text-align: center; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 4px;">
                    <p style="color: #64748b; margin-bottom: 1rem;">No images available in the media library.</p>
                    <a href="/admin/media" class="btn btn--primary">Go to Media Library &rarr;</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Caption</label>
            <input type="text" name="caption" placeholder="e.g. Custom Water-Cooled Gaming PC">
        </div>

        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" value="0">
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" checked> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Save to Gallery</button>
            <a href="/admin/gallery" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
