<?php
namespace App\Config;

use App\Core\Env;
use PDO;
use PDOException;

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            $host = Env::get('DB_HOST', '127.0.0.1');
            $db   = Env::get('DB_NAME', 'timlyne_db');
            $user = Env::get('DB_USER', 'root');
            $pass = Env::get('DB_PASS', '');
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                  // True prepared statements
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // In production, log this instead of echoing details
                die("Database connection failed. Please check your configuration.");
            }
        }
        return self::$instance;
    }
}
