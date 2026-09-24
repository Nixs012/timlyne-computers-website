<?php
$bName = $settings['business_name'] ?? 'Timlyne Computer Solutions Limited';
$bPhone1 = $settings['business_phone_1'] ?? '0724 407 638';
$bPhone2 = $settings['business_phone_2'] ?? '0707 302 212';
$bEmail = $settings['business_email'] ?? 'info@timlynecomputers.co.ke';
$bAddress = $settings['business_address'] ?? "Mwembe Tayari, Hiltop Plaza\n1st Floor, Shop No. M25\nMombasa, Kenya";
$bWhatsapp = $settings['business_whatsapp'] ?? '+254724407638';
$waMsg = $settings['whatsapp_default_message'] ?? 'Hello, I would like to know more about your products/services.';

$heroBadge = $settings['hero_badge'] ?? 'Hiltop Plaza (1st Floor Shop No. M25) . Mwembe Tayari . Mombasa · Kenya';
$heroHeadline = $settings['hero_headline'] ?? 'Your trusted partner for <span>computers & tech</span>';
$heroLead = $settings['hero_lead'] ?? 'Desktops, Laptops, Printers, CCTV, Networking Gear, UPS, Inks, Routers, Cables, Plotters, Heatpress Machines, Storage & more — all under one roof.';

$cleanWaNumber = preg_replace('/[^0-9]/', '', $bWhatsapp);
$waUrl = "https://wa.me/{$cleanWaNumber}?text=" . rawurlencode($waMsg);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?= \App\Core\Security::e($bName) ?> — Desktops, Laptops, Printers, CCTV, Networking & more in Mombasa, Kenya." />
  <title><?= \App\Core\Security::e($bName) ?> | Mombasa</title>
  <link rel="icon" href="assets/logo.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
  <style>
    .floating-wa {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        z-index: 1000;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .floating-wa:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(0,0,0,0.2);
    }
    .floating-wa svg {
        width: 35px;
        height: 35px;
        fill: currentColor;
    }
  </style>
</head>
<body>
  <header class="header" id="top">
    <div class="header__inner container">
      <a href="#top" class="logo" aria-label="<?= \App\Core\Security::e($bName) ?> — Home">
        <span class="logo__icon-wrap">
          <img src="assets/logo.png" alt="" class="logo__icon" width="44" height="44" />
        </span>
        <span class="logo__text">
          <strong class="logo__title">TIMLYNE</strong>
          <span class="logo__subtitle">Computer Solutions Limited</span>
        </span>
      </a>
      <nav class="nav" aria-label="Main navigation">
        <a href="#products">Shop</a>
        <a href="#categories">Categories</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
      </nav>
      <div class="header__actions">
        <button type="button" class="cart-btn" id="cartToggle" aria-label="Open shopping cart">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          <span class="cart-btn__count" id="cartCount">0</span>
        </button>
        <button type="button" class="menu-btn" id="menuBtn" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
    <div class="mobile-nav" id="mobileNav">
      <a href="#products">Shop</a>
      <a href="#categories">Categories</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="hero__bg"></div>
      <div class="container hero__content">
        <p class="hero__badge"><?= \App\Core\Security::e($heroBadge) ?></p>
        <h1><?= strip_tags($heroHeadline, '<span><br><strong><em>') ?></h1>
        <p class="hero__lead"><?= \App\Core\Security::e($heroLead) ?></p>
        <div class="hero__cta">
          <a href="#products" class="btn btn--primary">Browse products</a>
          <a href="#contact" class="btn btn--outline">Visit our shop</a>
        </div>
        <ul class="hero__stats">
          <li><strong>15+</strong><span>Product lines</span></li>
          <li><strong>100%</strong><span>Genuine parts</span></li>
          <li><strong>M25</strong><span>Hiltop Plaza, 1st Floor</span></li>
        </ul>
      </div>
    </section>

    <section class="categories" id="categories">
      <div class="container">
        <h2 class="section-title">Shop by category</h2>
        <div class="category-pills" id="categoryPills"></div>
      </div>
    </section>

    <section class="products" id="products">
      <div class="container">
        <div class="products__header">
          <h2 class="section-title">Featured products</h2>
          <div class="search-wrap">
            <input type="search" id="searchInput" placeholder="Search products..." aria-label="Search products" />
          </div>
        </div>
        <p class="products__count" id="productCount"></p>
        <div class="product-grid" id="productGrid"></div>
        <p class="empty-state" id="emptyState" hidden>No products match your search.</p>
      </div>
    </section>

    <section class="about" id="about">
      <div class="container about__grid">
        <div class="about__copy">
          <h2 class="section-title">About Timlyne</h2>
          <p><?= \App\Core\Security::e($bName) ?> is a full-service computer store in Mombasa, supplying businesses and individuals with quality hardware, accessories, and support.</p>
          <p>From Epson printers and original inks to Tenda and TP-Link networking, Lightwave UPS systems, CCTV installations, and complete desktop & laptop solutions — we stock what you need to stay connected and productive.</p>
        </div>
        <div class="about__card">
          <h3>What we offer</h3>
          <ul>
            <li>Desktops & Custom PC builds</li>
            <li>Laptops & Chargers</li>
            <li>Epson Printers & Original Inks</li>
            <li>Plotting Machines</li>
            <li>Heatpress Machines</li>
            <li>Laminating Machines</li>
            <li>CCTV & Surveillance</li>
            <li>Professional Cameras</li>
            <li>Networking & Routers</li>
            <li>Storage: HDD, SSD & Enclosures</li>
            <li>RAM, Cables & UPS</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="contact" id="contact">
      <div class="container contact__grid">
        <div>
          <h2 class="section-title section-title--light">Get in touch</h2>
          <p class="contact__tagline">Visit us or call — we're ready to help.</p>
        </div>
        <div class="contact__cards">
          <article class="contact-card">
            <h3>Location</h3>
            <p><?= nl2br(\App\Core\Security::e($bAddress)) ?></p>
          </article>
          <article class="contact-card">
            <h3>Phone</h3>
            <p><a href="tel:<?= preg_replace('/[^0-9+]/', '', $bPhone1) ?>"><?= \App\Core\Security::e($bPhone1) ?></a></p>
            <?php if ($bPhone2): ?>
            <p><a href="tel:<?= preg_replace('/[^0-9+]/', '', $bPhone2) ?>"><?= \App\Core\Security::e($bPhone2) ?></a></p>
            <?php endif; ?>
          </article>
          <article class="contact-card">
            <h3>Email</h3>
            <p><a href="mailto:<?= \App\Core\Security::e($bEmail) ?>"><?= \App\Core\Security::e($bEmail) ?></a></p>
          </article>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <p>&copy; <span id="year"></span> <?= \App\Core\Security::e($bName) ?>. All rights reserved.</p>
      <p><?= \App\Core\Security::e(str_replace("\n", " · ", $bAddress)) ?></p>
    </div>
  </footer>

  <aside class="cart-drawer" id="cartDrawer" aria-hidden="true">
    <div class="cart-drawer__overlay" id="cartOverlay"></div>
    <div class="cart-drawer__panel" role="dialog" aria-labelledby="cartTitle">
      <header class="cart-drawer__header">
        <h2 id="cartTitle">Your cart</h2>
        <button type="button" class="cart-drawer__close" id="cartClose" aria-label="Close cart">&times;</button>
      </header>
      <div class="cart-drawer__body" id="cartItems"></div>
      <footer class="cart-drawer__footer">
        <div class="cart-total">
          <span>Total</span>
          <strong id="cartTotal">Ksh 0</strong>
        </div>
        <button type="button" class="btn btn--primary btn--block" id="checkoutBtn">Request quote via WhatsApp</button>
        <button type="button" class="btn btn--ghost btn--block" id="clearCartBtn">Clear cart</button>
      </footer>
    </div>
  </aside>

  <!-- Floating WhatsApp Button -->
  <a href="<?= \App\Core\Security::e($waUrl) ?>" class="floating-wa" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
        <!-- Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157.1zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
    </svg>
  </a>

  <div class="toast" id="toast" role="status" aria-live="polite"></div>

  <script src="js/products.js"></script>
  <script src="js/app.js"></script>
</body>
</html>
