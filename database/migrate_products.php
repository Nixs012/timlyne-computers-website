<?php
require_once __DIR__ . '/../app/Core/Env.php';
require_once __DIR__ . '/../app/Config/Database.php';
use App\Core\Env;
use App\Config\Database;

Env::load(__DIR__ . '/../.env');
$db = Database::getConnection();

// Helper to generate a slug
function generateSlug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

// Read JSON
$json = file_get_contents(__DIR__ . '/../public/js/products.json');
$products = json_decode($json, true);

if (!$products) {
    die("Failed to parse products.json\n");
}

$categoryMap = [
    "CCTV'S" => "CCTV",
    "RAMS" => "RAM",
    "Plotter" => "Plotting Machines",
    "8-in-ONE Heatrpess" => "Heat Press Machines",
];

$insertedCount = 0;
$lapDuplicateCount = 0;

foreach ($products as $p) {
    $rawCategory = $p['category'];
    $categoryName = $categoryMap[$rawCategory] ?? $rawCategory;

    // Fix duplicate lap-001
    $id = $p['id'];
    if ($id === 'lap-001') {
        if ($lapDuplicateCount === 0) {
            $id = 'dsk-001'; // First one is the HP Desktop
        }
        $lapDuplicateCount++;
    }

    $name = $p['name'];
    $slug = generateSlug($name);
    $price = $p['price'];
    $icon = $p['icon'] ?? null;
    $image = $p['image'] ?? null; // Only lap-001 had this, but let's capture it
    $specs = isset($p['specs']) ? json_encode($p['specs']) : null;

    // Find category ID
    $stmt = $db->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$categoryName]);
    $catId = $stmt->fetchColumn();

    if (!$catId) {
        echo "WARNING: Category '$categoryName' not found for product '$name'\n";
        continue;
    }

    // Insert Product
    try {
        $stmt = $db->prepare("INSERT INTO products (id, category_id, name, slug, price, icon, image_path, specs) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $catId, $name, $slug, $price, $icon, $image, $specs]);
        $insertedCount++;
    } catch (\PDOException $e) {
        echo "Error inserting $id: " . $e->getMessage() . "\n";
    }
}

echo "Migration complete. Inserted $insertedCount products.\n";
