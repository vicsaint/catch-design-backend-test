<?php

declare(strict_types=1);

/**
 * file: CustomerRepository.php
 * author: victorS
 * description: class that creates the customer tables and insertion of the data importing from csv file, 
 *              and the function that generates the data upon searching with pagination logic
 * date: March 03, 2026
 */

namespace App;

use PDO;

final class CustomerRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function migrate(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                email TEXT NOT NULL,
                gender TEXT,
                ip_address TEXT,
                company TEXT,
                city TEXT,
                title TEXT,
                website TEXT,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $this->pdo->exec('CREATE INDEX IF NOT EXISTS idx_customers_last_name ON customers(last_name)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS idx_customers_email ON customers(email)');
    }

    public function truncate(): void
    {
        $this->pdo->exec('DELETE FROM customers');
    }

    //iterable accepts any value that can be looped with foreach
    public function import(iterable $rows): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO customers (
                id,
                first_name,
                last_name,
                email,
                gender,
                ip_address,
                company,
                city,
                title,
                website
            ) VALUES (
                :id,
                :first_name,
                :last_name,
                :email,
                :gender,
                :ip_address,
                :company,
                :city,
                :title,
                :website
            )'
        );

        $count = 0;
        $this->pdo->beginTransaction();

        try {
            foreach ($rows as $row) {
                $statement->execute([
                    ':id' => (int) $row['id'],
                    ':first_name' => $row['first_name'],
                    ':last_name' => $row['last_name'],
                    ':email' => $row['email'],
                    ':gender' => $this->nullable($row['gender']),
                    ':ip_address' => $this->nullable($row['ip_address']),
                    ':company' => $this->nullable($row['company']),
                    ':city' => $this->nullable($row['city']),
                    ':title' => $this->nullable($row['title']),
                    ':website' => $this->nullable($row['website']),
                ]);
                $count++;
            }

            $this->pdo->commit();
        } catch (\Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }

        return $count;
    }

    public function paginate(int $page, int $perPage, ?string $search = null): array
    {
        $offset = ($page - 1) * $perPage;
        $bindings = [];
        $whereClause = '';
        
    //WHERE first_name LIKE :first_name
    // OR last_name LIKE :last_name
    //OR email LIKE :email

        if ($search !== null && $search !== '') {
            $whereClause = 'WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR company LIKE :search OR city LIKE :search';
            $bindings[':search'] = '%' . $search . '%';
        }

        $countStatement = $this->pdo->prepare("SELECT COUNT(*) FROM customers {$whereClause}");
        $countStatement->execute($bindings);
        $total = (int) $countStatement->fetchColumn();

        $query = "SELECT id, first_name, last_name, email, gender, ip_address, company, city, title, website
            FROM customers
            {$whereClause}
            ORDER BY id ASC
            LIMIT :limit OFFSET :offset";

        $statement = $this->pdo->prepare($query);
        foreach ($bindings as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_STR);
        }
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'data' => $statement->fetchAll(),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => max(1, (int) ceil($total / $perPage)),
            ],
        ];
    }

    private function nullable(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
