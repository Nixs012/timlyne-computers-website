<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 - Page Not Found | Timlyne Computers</title>
  <link rel="stylesheet" href="/css/styles.css" />
  <style>
    .error-container {
        text-align: center;
        margin: 10rem auto;
        max-width: 600px;
        padding: 0 1rem;
    }
    .error-code {
        font-size: 8rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    .error-message {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="header__inner container">
      <a href="/" class="logo">
        <span class="logo__icon-wrap"><img src="/assets/logo.png" alt="" width="44" height="44" /></span>
        <span class="logo__text">
          <strong class="logo__title">TIMLYNE</strong>
          <span class="logo__subtitle">Computer Solutions Limited</span>
        </span>
      </a>
    </div>
  </header>

  <main>
    <div class="error-container">
      <div class="error-code">404</div>
      <p class="error-message">Oops! The page you're looking for doesn't exist or has been moved.</p>
      <a href="/" class="btn btn--primary">Back to Home</a>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <p>&copy; <?= date('Y') ?> Timlyne Computer Solutions Limited. All rights reserved. | <a href="/faq" style="color: inherit;">FAQ</a> | <a href="/gallery" style="color: inherit;">Gallery</a> | <a href="/privacy-policy" style="color: inherit;">Privacy Policy</a> | <a href="/terms-conditions" style="color: inherit;">Terms &amp; Conditions</a></p>
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
</body>
</html>
