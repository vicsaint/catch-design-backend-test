<?php

declare(strict_types=1);

/**
 * file: ApiResponse.php
 * author: victorS
 * description: class to convert data into JSON, data fomratting
 * date: March 03, 2026
 */

namespace App;

final class ApiResponse
{
    public static function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
