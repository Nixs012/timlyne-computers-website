<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?= \App\Core\Security::e($metaDescription) ?>" />
  <title><?= \App\Core\Security::e($title) ?> | <?= \App\Core\Security::e($bName) ?></title>
  <link rel="canonical" href="<?= \App\Core\Security::e($canonicalUrl) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= \App\Core\Security::e($title) ?>" />
  <meta property="og:description" content="<?= \App\Core\Security::e($metaDescription) ?>" />
  <meta property="og:url" content="<?= \App\Core\Security::e($canonicalUrl) ?>" />
  <link rel="icon" href="/assets/logo.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/styles.css" />
  <?php if (!empty($chatbotSettings['is_enabled'])): ?>
  <link rel="stylesheet" href="/css/chatbot.css" />
  <?php endif; ?>
  <style>
    .page-container {
        max-width: 800px;
        margin: calc(var(--header-h, 80px) + 3rem) auto 4rem;
        padding: 0 1rem;
    }
    .page-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: var(--text-color);
    }
    .page-content h1, .page-content h2, .page-content h3 {
        margin-top: 2rem;
        color: var(--primary-color);
    }
    .page-content p {
        margin-bottom: 1.5rem;
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
      <div class="page-content">
        <?= $page['content'] ?>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <p>&copy; <span id="year"><?= date('Y') ?></span> <?= \App\Core\Security::e($bName) ?>. All rights reserved. | <a href="/faq" style="color: inherit;">FAQ</a> | <a href="/gallery" style="color: inherit;">Gallery</a> | <a href="/privacy-policy" style="color: inherit;">Privacy Policy</a> | <a href="/terms-conditions" style="color: inherit;">Terms &amp; Conditions</a></p>
      <?php
        $socialLinks = array_filter([
            'Facebook' => $settings['social_facebook'] ?? '',
            'Instagram' => $settings['social_instagram'] ?? '',
            'Twitter' => $settings['social_twitter'] ?? '',
        ], static fn($url) => is_string($url) && trim($url) !== '');
      ?>
      <?php if ($socialLinks): ?>
      <p style="display: flex; justify-content: center; gap: 1rem;">
        <?php foreach ($socialLinks as $label => $url): ?>
        <a href="<?= \App\Core\Security::e($url) ?>" target="_blank" rel="noopener noreferrer" style="color: inherit;"><?= \App\Core\Security::e($label) ?></a>
        <?php endforeach; ?>
      </p>
      <?php endif; ?>
    </div>
  </footer>

  <a href="<?= \App\Core\Security::e($waUrl) ?>" class="floating-wa" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="35" height="35" fill="#fff">
        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157.1zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
    </svg>
  </a>
  <?php if (!empty($chatbotSettings['is_enabled'])): ?>
  <div class="chatbot-widget" data-chatbot-root data-welcome-message="<?= \App\Core\Security::e($chatbotSettings['welcome_message'] ?? '') ?>" data-csrf-token="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>" data-whatsapp-url="<?= \App\Core\Security::e(\App\Models\Setting::getWhatsAppUrl($settings, 'I would like to speak to a human')) ?>"></div>
  <script src="/js/chatbot.js" defer></script>
  <?php endif; ?>
</body>
</html>
