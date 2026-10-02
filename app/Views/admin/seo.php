<?php require __DIR__ . '/layout_header.php'; ?>

<style>
    .seo-section { margin: 0 0 1.5rem; padding: 0 0 1rem; border-bottom: 1px solid #e2e8f0; }
    .seo-section h2 { margin: 0 0 1rem; font-size: 1.15rem; }
    .seo-field { margin-bottom: 1rem; }
    .seo-field:last-child { margin-bottom: 0; }
    .seo-field small { display: block; margin-top: 0.35rem; color: #64748b; }
</style>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="flash-success"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<form action="/admin/seo" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">

    <section class="seo-section">
        <h2>Homepage SEO</h2>
        <div class="seo-field">
            <label for="home_meta_title">Meta title</label>
            <input id="home_meta_title" type="text" name="home_meta_title" value="<?= \App\Core\Security::e($settings['home_meta_title'] ?? '') ?>" placeholder="<?= \App\Core\Security::e($fallbacks['home_meta_title']) ?>">
        </div>
        <div class="seo-field">
            <label for="home_meta_description">Meta description</label>
            <textarea id="home_meta_description" name="home_meta_description" rows="3" placeholder="<?= \App\Core\Security::e($fallbacks['home_meta_description']) ?>"><?= \App\Core\Security::e($settings['home_meta_description'] ?? '') ?></textarea>
        </div>
    </section>

    <section class="seo-section">
        <h2>FAQ Page SEO</h2>
        <div class="seo-field">
            <label for="faq_meta_title">Meta title</label>
            <input id="faq_meta_title" type="text" name="faq_meta_title" value="<?= \App\Core\Security::e($settings['faq_meta_title'] ?? '') ?>" placeholder="<?= \App\Core\Security::e($fallbacks['faq_meta_title']) ?>">
        </div>
        <div class="seo-field">
            <label for="faq_meta_description">Meta description</label>
            <textarea id="faq_meta_description" name="faq_meta_description" rows="3" placeholder="<?= \App\Core\Security::e($fallbacks['faq_meta_description']) ?>"><?= \App\Core\Security::e($settings['faq_meta_description'] ?? '') ?></textarea>
        </div>
    </section>

    <section class="seo-section">
        <h2>Gallery Page SEO</h2>
        <div class="seo-field">
            <label for="gallery_meta_title">Meta title</label>
            <input id="gallery_meta_title" type="text" name="gallery_meta_title" value="<?= \App\Core\Security::e($settings['gallery_meta_title'] ?? '') ?>" placeholder="<?= \App\Core\Security::e($fallbacks['gallery_meta_title']) ?>">
        </div>
        <div class="seo-field">
            <label for="gallery_meta_description">Meta description</label>
            <textarea id="gallery_meta_description" name="gallery_meta_description" rows="3" placeholder="<?= \App\Core\Security::e($fallbacks['gallery_meta_description']) ?>"><?= \App\Core\Security::e($settings['gallery_meta_description'] ?? '') ?></textarea>
        </div>
    </section>

    <section class="seo-section">
        <h2>Search Console</h2>
        <div class="seo-field">
            <label for="google_search_console_verification">Google Search Console verification</label>
            <input id="google_search_console_verification" type="text" name="google_search_console_verification" value="<?= \App\Core\Security::e($settings['google_search_console_verification'] ?? '') ?>" placeholder="Paste the verification value from Google Search Console">
            <small>Rendered as a verification meta tag on the homepage when set.</small>
        </div>
    </section>

    <button type="submit">Save SEO Settings</button>
</form>
<?php require __DIR__ . '/layout_footer.php'; ?>