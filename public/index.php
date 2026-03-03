<?php

declare(strict_types=1); 
//echo 'testing loading it without the public/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Directory</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <main class="page">
        <section class="hero">
            <p class="eyebrow">Catch Design Backend Test</p>
            <h1>Customer Directory in PHP</h1>
            <p class="intro">
                The UI loads customer records asynchronously from a small JSON API backed by SQLite.
            </p>
        </section>

        <section class="panel">
            <form id="filters" class="filters">
                <label class="search-field">
                    <span>Search</span>
                    <input id="search" name="search" type="search" placeholder="Name, company, city, email">
                </label>
                <label class="search-field search-field--small">
                    <span>Per page</span>
                    <select id="per-page" name="per_page">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                </label>
            </form>

            <div id="status" class="status" aria-live="polite">Loading customers...</div>
            <div id="list" class="list" role="list"></div>  <!--added role to tell screens readers that this is a list -->

            <div class="pagination">
                <button id="previous" type="button">Previous</button>
                <span id="meta">Page 1</span>
                <button id="next" type="button">Next</button>
            </div>
        </section>
    </main>

    <script src="/app.js"></script>
</body>
</html>
