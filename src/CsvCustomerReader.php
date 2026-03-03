<?php

declare(strict_types=1);

/**
 * file: Config.php
 * author: victorS
 * description: simple class that reads a csv file
 * date: March 03, 2026
 */

namespace App;

use RuntimeException;

final class CsvCustomerReader
{
    public function read(string $path): iterable
    {
        if (!is_file($path)) {
            throw new RuntimeException("CSV file not found at {$path}");
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Unable to open CSV file at {$path}");
        }

        try {
            $header = fgetcsv($handle);
            if ($header === false) {
                return;
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($header)) {
                    continue;
                }

                yield array_combine($header, $row);
            }
        } finally {
            fclose($handle);
        }
    }
}
