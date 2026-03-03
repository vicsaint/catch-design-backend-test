<?php

declare(strict_types=1);

/**
 * file: setup.php
 * author: victorS
 * description: this is important in the installation process, to do the database table creation/truncate 
 * date: March 03, 2026
 */


require dirname(__DIR__) . '/bootstrap.php';

use App\CustomerRepository;
use App\Database;

$repository = new CustomerRepository(Database::connect());
$repository->migrate();

fwrite(STDOUT, "Database setup complete.\n");
