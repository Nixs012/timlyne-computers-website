<?php require __DIR__ . '/../layout.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Add New Product</h2>
</div>

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
            <label>Image Path</label>
            <input type="text" name="image_path" placeholder="/uploads/image.jpg">
            <small>Or copy a path from the <a href="/admin/media" target="_blank">Media Library</a>.</small>
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
