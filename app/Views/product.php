<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Core\Security::e($product['meta_title'] ?: $product['name'] . ' - ' . ($settings['business_name'] ?? 'Timlyne Computers')) ?></title>
    <meta name="description" content="<?= \App\Core\Security::e($product['meta_description'] ?: $product['description']) ?>">
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        @media (max-width: 768px) {
            .product-detail-container { grid-template-columns: 1fr; }
        }
        .product-image {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            min-height: 400px;
        }
        .product-image img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }
        .product-info h1 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }
        .product-description {
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .product-specs {
            list-style: none;
            padding: 0;
            margin-bottom: 2rem;
        }
        .product-specs li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .product-specs li::before {
            content: "✓";
            color: var(--primary-color);
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="/" style="text-decoration:none; color:inherit;">
                    <h1><?= \App\Core\Security::e($settings['business_name'] ?? 'TIMLYNE') ?></h1>
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="product-detail-container">
            <div class="product-image">
                <?php if ($product['image_path']): ?>
                    <img src="<?= \App\Core\Security::e($product['image_path']) ?>" alt="<?= \App\Core\Security::e($product['name']) ?>">
                <?php else: ?>
                    <?= \App\Core\Security::e($product['icon'] ?? '📦') ?>
                <?php endif; ?>
            </div>
            
            <div class="product-info">
                <div style="margin-bottom: 1rem; color: #666; font-size: 0.9rem;">
                    <a href="/#products" style="color:var(--primary-color); text-decoration:none;">Products</a> &gt; 
                    <?= \App\Core\Security::e($product['category_name']) ?>
                </div>
                
                <h1><?= \App\Core\Security::e($product['name']) ?></h1>
                <div class="product-price">Ksh <?= number_format($product['price']) ?></div>
                
                <?php if ($product['description']): ?>
                <div class="product-description">
                    <?= nl2br(\App\Core\Security::e($product['description'])) ?>
                </div>
                <?php endif; ?>

                <?php 
                    $specs = json_decode($product['specs'], true) ?? [];
                    if (!empty($specs)): 
                ?>
                <h3>Specifications:</h3>
                <ul class="product-specs">
                    <?php foreach ($specs as $spec): ?>
                        <li><?= \App\Core\Security::e($spec) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <a href="<?= $whatsappUrl ?>" target="_blank" class="cta-button" style="display:inline-block; margin-top:1rem;">
                    <span class="btn-icon">💬</span> Order via WhatsApp
                </a>
            </div>
        </div>
    </main>

    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>📍 <?= \App\Core\Security::e($settings['business_address'] ?? '') ?></p>
                <p>📞 <?= \App\Core\Security::e($settings['business_phone_1'] ?? '') ?></p>
                <p>📧 <?= \App\Core\Security::e($settings['business_email'] ?? '') ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= \App\Core\Security::e($settings['business_name'] ?? 'Timlyne Computer Solutions') ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
