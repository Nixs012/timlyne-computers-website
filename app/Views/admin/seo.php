<?php require __DIR__ . '/layout_header.php'; ?>

<style>
    .seo-section { margin: 0 0 1.5rem; padding: 0 0 1rem; border-bottom: 1px solid #e2e8f0; }
    .seo-section h2 { margin: 0 0 1rem; font-size: 1.15rem; }
    .seo-field { margin-bottom: 1rem; }
    .seo-field:last-child { margin-bottom: 0; }
    .seo-field small { display: block; margin-top: 0.35rem; color: #64748b; }
    .seo-health { max-width: 900px; margin: 0 0 2rem; padding: 1rem 0; border-bottom: 1px solid #cbd5e1; }
    .seo-health h2 { margin: 0 0 1rem; }
    .seo-health h3 { margin: 1rem 0 0.4rem; font-size: 1rem; }
    .seo-health ul { margin: 0.4rem 0 0; padding-left: 1.25rem; }
    .seo-health li { margin: 0.25rem 0; }
    .seo-health-status { margin: 0.4rem 0; }
</style>

<?php if ($msg = \App\Core\Session::get('success')): ?>
    <div class="flash-success"><?= \App\Core\Security::e($msg) ?></div>
    <?php \App\Core\Session::set('success', null); ?>
<?php endif; ?>

<section class="seo-health">
    <h2>SEO Health Check</h2>
    <p class="seo-health-status"><strong>robots.txt:</strong>
        <?php if ($robotsExists): ?>
            File exists. <a href="/robots.txt">Open robots.txt</a>
        <?php else: ?>
            File not found at public/robots.txt.
        <?php endif; ?>
    </p>
    <p class="seo-health-status"><strong>Sitemap:</strong>
        <a href="/sitemap.xml">/sitemap.xml</a>
        <?php if ($sitemapStatus === 200): ?>
            returned HTTP 200.
        <?php elseif ($sitemapStatus !== null): ?>
            returned HTTP <?= (int)$sitemapStatus ?>.
        <?php else: ?>
            Could not check the HTTP response in this request context.
        <?php endif; ?>
    </p>

    <h3>Published products missing SEO metadata: <?= (int)$seoWarnings['products']['count'] ?></h3>
    <p><?= (int)$seoWarnings['products']['empty_meta_description_count'] ?> have an empty meta description. <a href="/admin/products">Manage products</a></p>
    <ul>
        <?php foreach ($seoWarnings['products']['items'] as $item): ?>
            <li><a href="/admin/products/<?= rawurlencode($item['id']) ?>/edit"><?= \App\Core\Security::e($item['name']) ?> (<?= \App\Core\Security::e($item['slug']) ?>)</a></li>
        <?php endforeach; ?>
        <?php if (empty($seoWarnings['products']['items'])): ?><li>No affected products.</li><?php endif; ?>
    </ul>

    <h3>Published pages missing SEO metadata: <?= (int)$seoWarnings['pages']['count'] ?></h3>
    <p><?= (int)$seoWarnings['pages']['empty_meta_description_count'] ?> have an empty meta description. <a href="/admin/pages">Manage pages</a></p>
    <ul>
        <?php foreach ($seoWarnings['pages']['items'] as $item): ?>
            <li><a href="/admin/pages/<?= (int)$item['id'] ?>/edit"><?= \App\Core\Security::e($item['title']) ?></a></li>
        <?php endforeach; ?>
        <?php if (empty($seoWarnings['pages']['items'])): ?><li>No affected pages.</li><?php endif; ?>
    </ul>

    <h3>Media items missing alt text: <?= (int)$seoWarnings['media']['count'] ?></h3>
    <p><a href="/admin/media">Open Media Library</a></p>
    <ul>
        <?php foreach ($seoWarnings['media']['items'] as $item): ?>
            <li><a href="/admin/media"><?= \App\Core\Security::e(basename($item['file_path'])) ?></a></li>
        <?php endforeach; ?>
        <?php if (empty($seoWarnings['media']['items'])): ?><li>No affected media items.</li><?php endif; ?>
    </ul>

    <h3>Homepage metadata fields missing: <?= (int)$seoWarnings['homepage']['count'] ?></h3>
    <ul>
        <?php foreach ($seoWarnings['homepage']['items'] as $key): ?>
            <li><a href="#<?= \App\Core\Security::e($key) ?>"><?= \App\Core\Security::e(str_replace('_', ' ', $key)) ?></a> is empty; the homepage uses its hard-coded fallback.</li>
        <?php endforeach; ?>
        <?php if (empty($seoWarnings['homepage']['items'])): ?><li>Both homepage metadata fields are configured.</li><?php endif; ?>
    </ul>

    <h3>Google Search Console</h3>
    <p><?= $seoWarnings['google_search_console_verification_configured'] ? 'Verification is configured.' : 'Not configured (optional).'; ?></p>
</section>

<form action="/admin/seo" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>">

    <section id="homepage-seo" class="seo-section">
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