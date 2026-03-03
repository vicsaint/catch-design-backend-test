<?php

declare(strict_types=1);

/**
 * file: import.php
 * author: victorS
 * description: this is important in the installation process, to do database table creation/truncate and reading
 *              of the csv file records imorting them to that database table 
 * date: March 03, 2026
 */


require dirname(__DIR__) . '/bootstrap.php';

use App\Config;
use App\CsvCustomerReader;
use App\CustomerRepository;
use App\Database;

$repository = new CustomerRepository(Database::connect());
$repository->migrate();
$repository->truncate();

$reader = new CsvCustomerReader();
$count = $repository->import($reader->read(Config::CSV_PATH));

fwrite(STDOUT, "Imported {$count} customers.\n");
