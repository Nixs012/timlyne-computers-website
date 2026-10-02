<?php require __DIR__ . '/layout_header.php'; ?>

<p>FAQs shown to the chatbot are managed in the FAQs section.</p>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="flash-success"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<?php if ($msg = \App\Core\Session::get('error')): ?>
    <div class="flash-error"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('error', null); ?>
<?php endif; ?>

<form action="/admin/chatbot" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">

    <div>
        <label for="welcome_message">Welcome Message</label>
        <textarea id="welcome_message" name="welcome_message" rows="4"><?= \App\Core\Security::e($settings['welcome_message'] ?? '') ?></textarea>
    </div>

    <div>
        <label for="fallback_message">Fallback Message</label>
        <textarea id="fallback_message" name="fallback_message" rows="4"><?= \App\Core\Security::e($settings['fallback_message'] ?? '') ?></textarea>
    </div>

    <div class="checkbox-row">
        <input id="is_enabled" type="checkbox" name="is_enabled" value="1" <?= !empty($settings['is_enabled']) ? 'checked' : '' ?>>
        <label for="is_enabled">Chatbot Enabled</label>
    </div>

    <div class="checkbox-row">
        <input id="whatsapp_handoff_enabled" type="checkbox" name="whatsapp_handoff_enabled" value="1" <?= !empty($settings['whatsapp_handoff_enabled']) ? 'checked' : '' ?>>
        <label for="whatsapp_handoff_enabled">Enable WhatsApp Handoff</label>
    </div>

    <button type="submit">Save Chatbot Settings</button>
</form>
<?php require __DIR__ . '/layout_footer.php'; ?>