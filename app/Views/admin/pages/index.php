<?php
$title = 'Pages';
ob_start();
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Manage Pages</h2>
    <a href="/admin/pages/create" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">Create New Page</a>
</div>

<?php if (\App\Core\Session::has('success')): ?>
    <div style="background: #10b981; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::getFlash('success')) ?>
    </div>
<?php endif; ?>
<?php if (\App\Core\Session::has('error')): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::getFlash('error')) ?>
    </div>
<?php endif; ?>

<table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background: #f1f5f9; text-align: left;">
            <th style="padding: 1rem;">Title</th>
            <th style="padding: 1rem;">URL</th>
            <th style="padding: 1rem;">Status</th>
            <th style="padding: 1rem;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $p): ?>
        <tr style="border-top: 1px solid #e2e8f0;">
            <td style="padding: 1rem;"><?= \App\Core\Security::e($p['title']) ?></td>
            <td style="padding: 1rem;">/<?= \App\Core\Security::e($p['slug']) ?></td>
            <td style="padding: 1rem;">
                <?php if ($p['is_published']): ?>
                    <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.875rem;">Published</span>
                <?php else: ?>
                    <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.875rem;">Draft</span>
                <?php endif; ?>
            </td>
            <td style="padding: 1rem;">
                <a href="/admin/pages/<?= $p['id'] ?>/edit" style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">Edit</a>
                <form action="/admin/pages/<?= $p['id'] ?>/delete" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::getCsrfToken() ?>">
                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; font: inherit;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($pages)): ?>
        <tr>
            <td colspan="4" style="padding: 2rem; text-align: center; color: #64748b;">No pages found.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
