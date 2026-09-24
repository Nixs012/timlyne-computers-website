<?php
$content = ob_start();
?>
<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
    .form-group input, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .full-width { grid-column: span 2; }
    .btn { padding: 0.75rem 1.5rem; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .alert-success { background: #dcfce7; color: #166534; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
</style>

<h2>Business Settings</h2>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="alert-success"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<form action="/admin/business-settings" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">
    
    <div class="form-grid">
        <div class="form-group full-width">
            <label>Business Name</label>
            <input type="text" name="business_name" value="<?= \App\Core\Security::e($settings['business_name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Primary Phone</label>
            <input type="text" name="business_phone_1" value="<?= \App\Core\Security::e($settings['business_phone_1'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Secondary Phone</label>
            <input type="text" name="business_phone_2" value="<?= \App\Core\Security::e($settings['business_phone_2'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>WhatsApp Number</label>
            <input type="text" name="business_whatsapp" value="<?= \App\Core\Security::e($settings['business_whatsapp'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="business_email" value="<?= \App\Core\Security::e($settings['business_email'] ?? '') ?>">
        </div>
        <div class="form-group full-width">
            <label>Physical Address</label>
            <textarea name="business_address" rows="3"><?= \App\Core\Security::e($settings['business_address'] ?? '') ?></textarea>
        </div>
        <div class="form-group full-width">
            <label>Opening Hours</label>
            <textarea name="opening_hours" rows="2"><?= \App\Core\Security::e($settings['opening_hours'] ?? '') ?></textarea>
        </div>
        <div class="form-group full-width">
            <label>Google Maps Embed/URL</label>
            <input type="text" name="google_maps_url" value="<?= \App\Core\Security::e($settings['google_maps_url'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Facebook URL</label>
            <input type="url" name="social_facebook" value="<?= \App\Core\Security::e($settings['social_facebook'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Instagram URL</label>
            <input type="url" name="social_instagram" value="<?= \App\Core\Security::e($settings['social_instagram'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Twitter/X URL</label>
            <input type="url" name="social_twitter" value="<?= \App\Core\Security::e($settings['social_twitter'] ?? '') ?>">
        </div>
    </div>
    
    <button type="submit" class="btn">Save Settings</button>
</form>
<?php
$content = ob_get_clean();
require APP_PATH . '/Views/admin/layout.php';
