<?php require __DIR__ . '/layout_header.php'; ?>

<div class="header-action-bar" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Manage Categories</h2>
    <button class="btn btn--primary" onclick="document.getElementById('addCategoryForm').style.display='block'">+ Add Category</button>
</div>

<div class="card" id="addCategoryForm" style="display:none; margin-bottom: 2rem;">
    <h3>New Category</h3>
    <form action="/admin/categories" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required onkeyup="document.getElementById('slugInput').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
        </div>
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slugInput" required>
        </div>
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" value="0">
        </div>
        <button type="submit" class="btn btn--primary">Save</button>
        <button type="button" class="btn btn--ghost" onclick="document.getElementById('addCategoryForm').style.display='none'">Cancel</button>
    </form>
</div>

<div class="card">
    <div class="content-wrapper">
        <table class="data-table" style="width:100%; text-align:left; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 1rem; border-bottom: 1px solid #eee;">Name</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #eee;">Slug</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #eee;">Sort</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #eee;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($cat['name']) ?></td>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee;"><code><?= \App\Core\Security::e($cat['slug']) ?></code></td>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= (int)$cat['sort_order'] ?></td>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                        <!-- Inline Edit Form (simplistic for now) -->
                        <form action="/admin/categories/<?= $cat['id'] ?>/delete" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                            <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="color: #64748b; font-size: 0.875rem;">
        Showing <?= count($categories) ?> of <?= $total ?> categories
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="btn btn--ghost">Previous</a>
        <?php endif; ?>
        <?php if ($total > $page * $perPage): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn btn--ghost">Next</a>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/layout_footer.php'; ?>
