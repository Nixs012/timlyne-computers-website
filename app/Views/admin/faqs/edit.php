<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Edit FAQ: <?= \App\Core\Security::e($faq['question']) ?></h2>
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
    <form action="/admin/faqs/<?= $faq['id'] ?>/update" method="POST" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">

        <div class="form-group">
            <label>Question</label>
            <input type="text" name="question" required value="<?= \App\Core\Security::e($faq['question']) ?>">
        </div>

        <div class="form-group">
            <label>Answer</label>
            <textarea name="answer" rows="5" required style="font-family: sans-serif;"><?= \App\Core\Security::e($faq['answer']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" value="<?= $faq['sort_order'] ?>">
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="is_published" value="1" <?= $faq['is_published'] ? 'checked' : '' ?>> Published
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn--primary">Update FAQ</button>
            <a href="/admin/faqs" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
