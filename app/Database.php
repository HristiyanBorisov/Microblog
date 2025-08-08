<?php

namespace App;

use PDO;

class Database {
    public static function connect(?string $database = ''): PDO
    {
        $pdo = new PDO(
            'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . ($database ?: $_ENV['DB_NAME']),
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    }
}
