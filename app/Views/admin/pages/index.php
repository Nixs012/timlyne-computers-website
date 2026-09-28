<?php require __DIR__ . '/../layout.php'; ?>

<div class="header-action-bar" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Manage Pages</h2>
    <a href="/admin/pages/create" class="btn btn--primary">+ Create New Page</a>
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
            <tr>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Title</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">URL</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Status</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $p): ?>
            <tr>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($p['title']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">/<?= \App\Core\Security::e($p['slug']) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <?php if ($p['is_published']): ?>
                        <span style="color: green; font-weight: 500;">Published</span>
                    <?php else: ?>
                        <span style="color: gray; font-weight: 500;">Draft</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <a href="/admin/pages/<?= $p['id'] ?>/edit" style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">Edit</a>
                    <form action="/admin/pages/<?= $p['id'] ?>/delete" method="POST" style="display: inline;" onsubmit="return confirm('Delete this page?');">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                        <button type="submit" style="color:red; background:none; border:none; cursor:pointer; font: inherit;">Delete</button>
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
</div>
