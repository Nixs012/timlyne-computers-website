<?php
require_once __DIR__ . '/../app/Core/Env.php';
require_once __DIR__ . '/../app/Config/Database.php';
use App\Core\Env;
use App\Config\Database;

Env::load(__DIR__ . '/../.env');
$db = Database::getConnection();
try {
    $db->exec('ALTER TABLE products ADD COLUMN description TEXT AFTER price, ADD COLUMN meta_title VARCHAR(255) AFTER specs, ADD COLUMN meta_description TEXT AFTER meta_title');
    echo "Altered table successfully.\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
