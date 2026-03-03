<?php

declare(strict_types=1); //proper data typing

/**
 * file: customers.php
 * author: victorS
 * description: API endpoint like a controller that will handle the pagination control and return a JSON data
 * date: March 03, 2026
 */

require dirname(__DIR__, 2) . '/bootstrap.php';  
use App\ApiResponse;
use App\Config;
use App\CustomerRepository;
use App\Database;

//pagination control
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$perPage = filter_input(INPUT_GET, 'per_page', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: Config::DEFAULT_PER_PAGE;
$perPage = min($perPage, Config::MAX_PER_PAGE);

$search = trim((string) ($_GET['search'] ?? ''));
$search = $search === '' ? null : $search;

$repository = new CustomerRepository(Database::connect());

//this will check if table has been created
//$repository->migrate(); 

//formatting to JSON
ApiResponse::json($repository->paginate($page, $perPage, $search));
