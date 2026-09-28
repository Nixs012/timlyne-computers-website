<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="margin:0;">Manage Gallery</h2>
    <a href="/admin/gallery/create" class="btn btn--primary">+ Add Image</a>
</div>

<?php if (\App\Core\Session::get('success')): ?>
    <div style="background: #10b981; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::get('success')) ?>
    </div>
<?php endif; ?>
<?php if (\App\Core\Session::get('error')): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::get('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background: #f1f5f9;">
            <tr style="border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Image</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Caption</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Status</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Order</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gallery as $item): ?>
            <tr style="border-top: 1px solid #e2e8f0;">
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <img src="<?= \App\Core\Security::e($item['image_url'] ?? '') ?>" alt="<?= \App\Core\Security::e($item['alt_text'] ?? '') ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($item['caption']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <?php if ($item['is_published']): ?>
                        <span style="color: green; font-weight: 500;">Published</span>
                    <?php else: ?>
                        <span style="color: gray; font-weight: 500;">Draft</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= $item['sort_order'] ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <form action="/admin/gallery/<?= $item['id'] ?>/move" method="POST" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" title="Move Up" style="cursor:pointer; border:none; background:none;">↑</button>
                        </form>
                        <form action="/admin/gallery/<?= $item['id'] ?>/move" method="POST" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" title="Move Down" style="cursor:pointer; border:none; background:none;">↓</button>
                        </form>
                        <a href="/admin/gallery/<?= $item['id'] ?>/edit" style="color: #3b82f6; text-decoration: none; margin-left: 0.5rem;">Edit</a>
                        <form action="/admin/gallery/<?= $item['id'] ?>/delete" method="POST" style="display: inline;" onsubmit="return confirm('Delete this gallery entry?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                            <button type="submit" style="color:red; background:none; border:none; cursor:pointer; font: inherit;">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($gallery)): ?>
            <tr>
                <td colspan="5" style="padding: 2rem; text-align: center; color: #64748b;">No gallery images found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
