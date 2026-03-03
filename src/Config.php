<?php

declare(strict_types=1);

/**
 * file: Config.php
 * author: victorS
 * description: class that holds the global config variables
 * date: March 03, 2026
 */

namespace App;

final class Config
{
    public const DATABASE_PATH = __DIR__ . '/../storage/database.sqlite';
    public const CSV_PATH = __DIR__ . '/../data/customers.csv';
    public const DEFAULT_PER_PAGE = 25;
    public const MAX_PER_PAGE = 100;
}
