<?php

declare(strict_types=1);

/**
 * file: Database.php
 * author: victorS
 * description: simple class to connect to sqlite db 
 * date: March 03, 2026
 */

namespace App;

use PDO;

final class Database
{
    public static function connect(): PDO
    {
        $directory = dirname(Config::DATABASE_PATH);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $pdo = new PDO('sqlite:' . Config::DATABASE_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}
