<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    // Shared PDO instance for the whole request.
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Read DB connection values from environment.
            $host = 'localhost';
            $port = $_ENV['MYSQLPORT'];
            $db   = $_ENV['MYSQL_DATABASE'] ;
            $user = $_ENV['MYSQLUSER'];
            $pass = $_ENV['MYSQLPASSWORD'];

            try {
                // Create PDO connection with strict error handling.
                self::$instance = new PDO(
                    "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                throw new \RuntimeException('DB Connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
