<?php require __DIR__ . '/../layout.php'; ?>

<div class="header-action-bar" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Products</h2>
    <a href="/admin/products/create" class="btn btn--primary">+ Add Product</a>
</div>

<div class="card">
    <table class="data-table" style="width:100%; text-align:left; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">ID</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Name</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Category</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Price</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Status</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><code><?= \App\Core\Security::e($p['id']) ?></code></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($p['name']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($p['category_name']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">Ksh <?= number_format($p['price']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <?php if ($p['is_published']): ?>
                        <span style="color: green;">Published</span>
                    <?php else: ?>
                        <span style="color: gray;">Draft</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <a href="/admin/products/<?= $p['id'] ?>/edit" style="color: blue; text-decoration: none; margin-right: 1rem;">Edit</a>
                    <form action="/admin/products/<?= $p['id'] ?>/delete" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                        <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
