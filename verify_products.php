<?php
require_once __DIR__ . '/app/Core/Env.php';
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Models/Product.php';
require_once __DIR__ . '/app/Models/Category.php';

use App\Core\Env;
use App\Models\Product;
use App\Models\Category;

Env::load(__DIR__ . '/.env');

echo "1. Checking if test-prod exists...\n";
$existing = Product::findById('test-prod');
if ($existing) {
    Product::delete('test-prod');
}

echo "2. Fetching API products...\n";
$apiUrl = 'http://localhost:8000/api/products';
$apiResponse = file_get_contents($apiUrl);
$products = json_decode($apiResponse, true);
$found = array_filter($products, fn($p) => $p['id'] === 'test-prod');
if (count($found) > 0) {
    die("Error: test-prod already in API response.\n");
}
echo "   - Test product not in API.\n";

echo "3. Creating test product...\n";
$cats = Category::getAll();
$catId = $cats[0]['id'];
Product::create('test-prod', $catId, 'Test Product', 'test-product', 999.99, 'Test desc', '🚀', '', json_encode([]), '', '', 1);

echo "4. Fetching API products again...\n";
$apiResponse = file_get_contents($apiUrl);
$products = json_decode($apiResponse, true);
$found = array_filter($products, fn($p) => $p['id'] === 'test-prod');
if (count($found) === 0) {
    die("Error: test-prod not found in API response after creation.\n");
}
echo "   - Test product found in API!\n";

echo "5. Fetching product page...\n";
$pageUrl = 'http://localhost:8000/products/test-product';
$pageHtml = file_get_contents($pageUrl);
if (strpos($pageHtml, 'Test Product') === false || strpos($pageHtml, '1,000') === false) {
    die("Error: Product details not found on product page.\n");
}
echo "   - Product page loaded successfully.\n";

echo "6. Deleting test product...\n";
Product::delete('test-prod');

echo "7. Fetching API products final check...\n";
$apiResponse = file_get_contents($apiUrl);
$products = json_decode($apiResponse, true);
$found = array_filter($products, fn($p) => $p['id'] === 'test-prod');
if (count($found) > 0) {
    die("Error: test-prod still in API response after deletion.\n");
}
echo "   - Test product successfully removed from API.\n";

echo "8. Fetching product page final check...\n";
$headers = @get_headers($pageUrl);
if (strpos($headers[0], '404') === false) {
    echo "Warning: Expected 404 response, but got " . $headers[0] . "\n";
} else {
    echo "   - Product page returned 404 as expected.\n";
}

echo "\nVerification complete!\n";
