<?php

namespace App\Data;

use App\Data\DBConfig;
use PDO;
use PDOException;
use Exception;

class DatabaseConnection {
    private static ?PDO $instance = null;

    private function __construct() {
        
    }

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception('Database connection error: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
