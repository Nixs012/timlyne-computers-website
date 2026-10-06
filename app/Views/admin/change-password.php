<?php
$title = "Change Password";
require APP_PATH . '/Views/admin/layout_header.php';
?>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="flash-success">
        <?= \App\Core\Security::e($msg) ?>
    </div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<?php if ($msg = \App\Core\Session::get('error')): ?>
    <div class="flash-error">
        <?= \App\Core\Security::e($msg) ?>
    </div>
    <?php \App\Core\Session::set('error', null); ?>
<?php endif; ?>

<form action="/admin/change-password" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">

    <div>
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" id="current_password" required>
    </div>

    <div>
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" required>
        <small style="display: block; color: #64748b; margin-top: 0.25rem; font-size: 0.8rem;">Must be at least 8 characters.</small>
    </div>

    <div>
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
    </div>

    <div class="actions">
        <button type="submit">Change Password</button>
        <a href="/admin" class="cancel">Cancel</a>
    </div>
</form>

<?php
require APP_PATH . '/Views/admin/layout_footer.php';
