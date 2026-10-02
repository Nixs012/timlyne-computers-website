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
  <?php if (!empty($chatbotSettings['is_enabled'])): ?>
  <link rel="stylesheet" href="/css/chatbot.css" />
  <?php endif; ?>
  <?php if (!empty($faqs)): ?>
  <script type="application/ld+json">
    <?php
    $jsonLd = [
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => array_map(fn($f) => [
            "@type" => "Question",
            "name" => $f['question'],
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => $f['answer']
            ]
        ], $faqs)
    ];
    echo json_encode($jsonLd, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    ?>
  </script>
  <?php endif; ?>
  <style>
    .page-container {
        max-width: 800px;
        margin: calc(var(--header-h, 80px) + 3rem) auto 4rem;
        padding: 0 1rem;
    }
    .faq-list {
        display: grid;
        gap: 1rem;
        margin-top: 2rem;
    }
    details {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }
    summary {
        padding: 1rem;
        font-weight: 600;
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }
    summary::-webkit-details-marker { display: none; }
    summary::after {
        content: '→';
        transition: transform 0.2s;
    }
    details[open] summary::after {
        transform: rotate(90deg);
    }
    .faq-answer {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        line-height: 1.6;
        color: var(--text-color);
    }
    .empty-faq {
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
  <?php if (!empty($chatbotSettings['is_enabled'])): ?>
  <div class="chatbot-widget" data-chatbot-root data-welcome-message="<?= \App\Core\Security::e($chatbotSettings['welcome_message'] ?? '') ?>" data-csrf-token="<?= \App\Core\Security::e(\App\Core\Security::generateCsrfToken()) ?>" data-whatsapp-url="<?= \App\Core\Security::e(\App\Models\Setting::getWhatsAppUrl($settings, 'I would like to speak to a human')) ?>"></div>
  <script src="/js/chatbot.js" defer></script>
  <?php endif; ?>
        <a href="/#products">Shop</a>
        <a href="/#about">About</a>
        <a href="/#contact">Contact</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="page-container">
      <h1 class="section-title"><?= \App\Core\Security::e($title) ?></h1>

      <?php if (!empty($faqs)): ?>
        <div class="faq-list">
          <?php foreach ($faqs as $f): ?>
          <details>
            <summary><?= \App\Core\Security::e($f['question']) ?></summary>
            <div class="faq-answer">
              <?= nl2br(\App\Core\Security::e($f['answer'])) ?>
            </div>
          </details>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-faq">
          <p>We don't have any FAQs listed at the moment.</p>
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
