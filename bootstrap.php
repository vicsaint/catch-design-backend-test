<?php

declare(strict_types=1);

$projectRoot = __DIR__;
/**
 * 
 * description: this will load classess properly int this format App\.. similar to Laravel and the mapping
 *              is coming from src/ folder
 * 
 */

spl_autoload_register(static function (string $class) use ($projectRoot): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = $projectRoot . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

date_default_timezone_set('UTC');
