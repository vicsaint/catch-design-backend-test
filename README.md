# catch-design-backend-test 
- Backend Developer Test Solution (PHP)
This repository contains a small PHP application that completes the brief using:

- PHP 8.3
- SQLite via PDO
- A CLI import script for the provided CSV
- A paginated JSON API
- A lightweight browser UI that loads data asynchronously with `fetch`


## Project Structure

```text
.
.
├── bootstrap.php
├── data/
│   └── customers.csv
├── public/
│   ├── api/customers.php
│   ├── app.js
│   ├── index.php
│   └── styles.css
├── scripts/
│   ├── import.php
│   └── setup.php
└── src/
    ├── ApiResponse.php
    ├── Config.php
    ├── CsvCustomerReader.php
    ├── CustomerRepository.php
    └── Database.php
```

## Requirements

- PHP 8.3 or newer
- `pdo_sqlite` enabled

No separate database server is required. The application uses SQLite, so the database is created locally as a file at `storage/database.sqlite` when the setup or import script runs.

## Setup

1. Clone the repository.
2. Create the SQLite database schema:

   ```bash
   php scripts/setup.php
   ```

3. Import the CSV data into the database:

   ```bash
   php scripts/import.php
   ```

4. Start the local PHP server:

   ```bash
   php -S localhost:8000 -t public
   ```

5. Open `http://localhost:8000` or `http://localhost:8000/public`. 

If `pdo_sqlite` is enabled in PHP, these commands are enough to create and use the database. There is no separate SQLite service to install or start.

## API

Endpoint:

```text
GET /api/customers.php or http://localhost:8000/api/customers.php
```

Query parameters:

- `page`: page number, minimum `1`
- `per_page`: page size, minimum `1`, maximum `100`
- `search`: optional free-text search across first name, last name, email, company, and city

Example:

```text
/api/customers.php?page=2&per_page=25&search=laura
```
http://localhost:8000/api/customers.php
Example response shape:

```json
{
  "data": [
    {
      "id": 1,
      "first_name": "Laura",
      "last_name": "Richards",
      "email": "lrichards0@reverbnation.com",
      "gender": "Female",
      "ip_address": "81.192.7.99",
      "company": "Meezzy",
      "city": "KallithÃ©a",
      "title": "Biostatistician III",
      "website": "https://intel.com/..."
    }
  ],
  "pagination": {
    "page": 1,
    "per_page": 25,
    "total": 1000,
    "total_pages": 40
  }
}
```

## Notes

- The importer truncates the `customers` table before inserting records, so re-running it is deterministic.
- Input handling is constrained with PHP validation for pagination parameters.
- The search feature uses prepared statements to avoid SQL injection.
- SQLite was chosen to keep the test easy to run without external services.

## Verification

Run these commands after setup:

```bash
php scripts/setup.php
php scripts/import.php
php -S localhost:8000 -t public
```

Then verify:

- the homepage renders
- `/api/customers.php?page=1&per_page=10` returns JSON
- pagination and search both work in the browser
