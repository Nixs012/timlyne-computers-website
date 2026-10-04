<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2 style="margin:0;">Edit Gallery Item: <?= \App\Core\Security::e($item['caption'] ?: 'Untitled') ?></h2>
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
    <form action="/admin/gallery/<?= $item['id'] ?>/update" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">

        <div class="form-group">
            <label>Selected Image</label>
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
                <img src="<?= \App\Core\Security::e($item['image_url'] ?? '') ?>" alt="<?= \App\Core\Security::e($item['alt_text'] ?? '') ?>" style="width: 120px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                <div>
                    <strong style="display: block; color: #475569;">Image Path:</strong>
                    <span style="font-size: 0.85rem; color: #64748b;"><?= \App\Core\Security::e($item['image_url'] ?? '') ?></span>
                </div>
            </div>
            <small style="color: #64748b; margin-top: 0.5rem; display: block;">Image cannot be changed here. Upload a new image and remove this entry if needed.</small>
        </div>

        <div class="form-group">
            <label>Caption</label>
            <input type="text" name="caption" value="<?= \App\Core\Security::e($item['caption']) ?>">
        </div>

        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" value="<?= $item['sort_order'] ?>">
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" <?= $item['is_published'] ? 'checked' : '' ?>> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Update Gallery Item</button>
            <a href="/admin/gallery" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
