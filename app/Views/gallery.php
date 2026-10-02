<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?= \App\Core\Security::e($metaDescription) ?>" />
  <title><?= \App\Core\Security::e($metaTitle) ?></title>
  <link rel="canonical" href="<?= \App\Core\Security::e($canonicalUrl) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= \App\Core\Security::e($metaTitle) ?>" />
  <meta property="og:description" content="<?= \App\Core\Security::e($metaDescription) ?>" />
  <meta property="og:url" content="<?= \App\Core\Security::e($canonicalUrl) ?>" />
  <link rel="icon" href="/assets/logo.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/styles.css" />
  <style>
    .page-container {
        max-width: 1000px;
        margin: calc(var(--header-h, 80px) + 3rem) auto 4rem;
        padding: 0 1rem;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    .gallery-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease;
    }
    .gallery-item:hover {
        transform: translateY(-4px);
    }
    .image-container {
        width: 100%;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f8fafc;
    }
    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .gallery-caption {
        padding: 1rem;
        font-size: 0.95rem;
        color: var(--text-color);
        line-height: 1.5;
        border-top: 1px solid #e2e8f0;
    }
    .empty-gallery {
        text-align: center;
        padding: 3rem 0;
    }
  </style>
</head>
<body>
  <header class="header" id="top">
    <div class="header__inner container">
      <a href="/" class="logo" aria-label="<?= \App\Core\Security::e($bName) ?> — Home">
        <span class="logo__icon-wrap">
          <img src="/assets/logo.png" alt="" class="logo__icon" width="44" height="44" />
        </span>
        <span class="logo__text">
          <strong class="logo__title">TIMLYNE</strong>
          <span class="logo__subtitle">Computer Solutions Limited</span>
        </span>
      </a>
      <nav class="nav" aria-label="Main navigation">
        <a href="/">Home</a>
        <a href="/#products">Shop</a>
        <a href="/#about">About</a>
        <a href="/#contact">Contact</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="page-container">
      <h1 class="section-title"><?= \App\Core\Security::e($title) ?></h1>

      <?php if (!empty($gallery)): ?>
        <div class="gallery-grid">
          <?php foreach ($gallery as $item): ?>
            <figure class="gallery-item">
              <div class="image-container">
                <img src="<?= \App\Core\Security::e($item['file_path']) ?>"
                     alt="<?= \App\Core\Security::e($item['alt_text'] ?? ($item['caption'] ?? '')) ?>"
                     loading="lazy" />
              </div>
              <?php if (!empty($item['caption'])): ?>
                <figcaption class="gallery-caption">
                  <?= \App\Core\Security::e($item['caption']) ?>
                </figcaption>
              <?php endif; ?>
            </figure>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-gallery">
          <p>We don't have any gallery images listed at the moment.</p>
          <a href="<?= \App\Core\Security::e($waUrl) ?>" class="btn btn--primary" target="_blank" rel="noopener noreferrer">Chat with us on WhatsApp</a>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <p>&copy; <span id="year"><?= date('Y') ?></span> <?= \App\Core\Security::e($bName) ?>. All rights reserved. | <a href="/faq" style="color: inherit;">FAQ</a> | <a href="/gallery" style="color: inherit;">Gallery</a> | <a href="/privacy-policy" style="color: inherit;">Privacy Policy</a> | <a href="/terms-conditions" style="color: inherit;">Terms &amp; Conditions</a></p>
    </div>
  </footer>
</body>
</html>
