<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Add New Product</h2>
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
    <form action="/admin/products" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">

        <div class="form-group">
            <label>Product ID (e.g., lap-004)</label>
            <input type="text" name="id" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= \App\Core\Security::e($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required onkeyup="document.getElementById('slugInput').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
        </div>

        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slugInput" required>
        </div>

        <div class="form-group">
            <label>Price (Ksh)</label>
            <input type="number" name="price" required step="0.01">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"></textarea>
        </div>

        <div class="form-group">
            <label>Icon (Emoji)</label>
            <input type="text" name="icon" placeholder="e.g. 💻">
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="hidden" name="image_path" id="imagePathInput" value="">
            <?php if (!empty($media)): ?>
                <div id="mediaPicker" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; margin-top: 0.5rem; max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0; padding: 1rem; border-radius: 4px; background: #f8fafc;">
                    <?php foreach ($media as $m): ?>
                    <div class="media-thumb" data-path="<?= \App\Core\Security::e($m['file_path']) ?>"
                         style="cursor: pointer; border: 2px solid #ddd; padding: 4px; border-radius: 4px; background: white; text-align: center; transition: all 0.2s;">
                        <img src="<?= \App\Core\Security::e($m['file_path']) ?>" alt="<?= \App\Core\Security::e($m['alt_text'] ?? '') ?>"
                             style="width: 100%; height: 100px; object-fit: cover; border-radius: 2px; pointer-events: none;">
                        <div style="font-size: 0.7rem; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #64748b;">
                            <?= \App\Core\Security::e(basename($m['file_path'])) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <style>
                    .media-thumb.is-selected { border-color: #3b82f6 !important; background: #eff6ff !important; box-shadow: 0 0 0 2px #3b82f6; }
                </style>
                <script>
                document.querySelectorAll('#mediaPicker .media-thumb').forEach(function(el) {
                    el.addEventListener('click', function() {
                        document.querySelectorAll('#mediaPicker .media-thumb').forEach(function(t) { t.classList.remove('is-selected'); });
                        el.classList.add('is-selected');
                        document.getElementById('imagePathInput').value = el.dataset.path;
                        document.getElementById('imagePathOverride').value = el.dataset.path;
                    });
                });
                </script>
            <?php else: ?>
                <div style="padding: 1.5rem; text-align: center; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 4px; margin-top: 0.5rem;">
                    <p style="color: #64748b; margin-bottom: 0.75rem;">No images in the Media Library yet.</p>
                    <a href="/admin/media" target="_blank" class="btn btn--primary">Upload images &rarr;</a>
                </div>
            <?php endif; ?>
            <div style="margin-top: 0.75rem;">
                <label style="font-size: 0.85rem; color: #64748b;">Or enter path manually:</label>
                <input type="text" id="imagePathOverride" placeholder="/uploads/image.jpg"
                       oninput="document.getElementById('imagePathInput').value = this.value;">
            </div>
        </div>

        <div class="form-group">
            <label>Specs (One per line)</label>
            <textarea name="specs" rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"></textarea>
        </div>

        <h3 style="margin-top: 2rem;">SEO Settings</h3>
        <div class="form-group">
            <label>Meta Title</label>
            <input type="text" name="meta_title">
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="meta_description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"></textarea>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" checked> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Save Product</button>
            <a href="/admin/products" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
