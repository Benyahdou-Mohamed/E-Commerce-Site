<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('MYSQLHOST')     ?: 'localhost';
            $db   = getenv('MYSQL_DATABASE') ?: 'railway';
            $user = getenv('MYSQLUSER')     ?: 'root';
            $pass = getenv('MYSQLPASSWORD') ?: '';
            $port = getenv('MYSQLPORT')     ?: '3306';

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
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
