<?php
$title = 'Create Page';
ob_start();
?>
<div style="max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h2>Create Page</h2>
    
    <?php if (\App\Core\Session::has('error')): ?>
        <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
            <?= \App\Core\Security::e(\App\Core\Session::getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/pages" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::getCsrfToken() ?>">
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Title</label>
            <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Slug (URL path)</label>
            <input type="text" name="slug" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Content (HTML supported)</label>
            <textarea name="content" rows="10" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-family: monospace;"></textarea>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Meta Title (SEO)</label>
            <input type="text" name="meta_title" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Meta Description (SEO)</label>
            <textarea name="meta_description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;"></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_published" value="1" checked>
                Published (visible on public site)
            </label>
        </div>

        <div>
            <button type="submit" style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">Save Page</button>
            <a href="/admin/pages" style="display: inline-block; margin-left: 1rem; color: #64748b; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
