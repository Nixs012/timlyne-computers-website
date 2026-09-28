<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Create Page</h2>
</div>

<?php if (\App\Core\Session::get('error')): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::get('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <form action="/admin/pages" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Slug (URL path)</label>
            <input type="text" name="slug" required>
        </div>

        <div class="form-group">
            <label>Content (HTML supported)</label>
            <textarea name="content" rows="10" style="font-family: monospace;"></textarea>
        </div>

        <div class="form-group">
            <label>Meta Title (SEO)</label>
            <input type="text" name="meta_title">
        </div>

        <div class="form-group">
            <label>Meta Description (SEO)</label>
            <textarea name="meta_description" rows="3"></textarea>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" checked> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Save Page</button>
            <a href="/admin/pages" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
