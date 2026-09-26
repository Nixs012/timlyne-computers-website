<?php require __DIR__ . '/../layout.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Edit Product</h2>
</div>

<div class="card">
    <form action="/admin/products/<?= \App\Core\Security::e($product['id']) ?>/update" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
        
        <div class="form-group">
            <label>Product ID</label>
            <input type="text" value="<?= \App\Core\Security::e($product['id']) ?>" disabled style="background: #f5f5f5;">
            <small>ID cannot be changed.</small>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= \App\Core\Security::e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= \App\Core\Security::e($product['name']) ?>" required onkeyup="document.getElementById('slugInput').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
        </div>

        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slugInput" value="<?= \App\Core\Security::e($product['slug']) ?>" required>
        </div>

        <div class="form-group">
            <label>Price (Ksh)</label>
            <input type="number" name="price" value="<?= \App\Core\Security::e($product['price']) ?>" required step="0.01">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"><?= \App\Core\Security::e($product['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Icon (Emoji)</label>
            <input type="text" name="icon" value="<?= \App\Core\Security::e($product['icon'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Image Path</label>
            <input type="text" name="image_path" value="<?= \App\Core\Security::e($product['image_path'] ?? '') ?>">
            <small>Or copy a path from the <a href="/admin/media" target="_blank">Media Library</a>.</small>
        </div>

        <div class="form-group">
            <label>Specs (One per line)</label>
            <?php 
                $specsArray = json_decode($product['specs'], true) ?? []; 
                $specsString = implode("\n", $specsArray);
            ?>
            <textarea name="specs" rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"><?= \App\Core\Security::e($specsString) ?></textarea>
        </div>

        <h3 style="margin-top: 2rem;">SEO Settings</h3>
        <div class="form-group">
            <label>Meta Title</label>
            <input type="text" name="meta_title" value="<?= \App\Core\Security::e($product['meta_title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="meta_description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"><?= \App\Core\Security::e($product['meta_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" <?= $product['is_published'] ? 'checked' : '' ?>> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Update Product</button>
            <a href="/admin/products" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
