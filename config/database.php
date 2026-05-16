<?php
declare(strict_types=1);

return [
    'host'     => env('DB_HOST', 'localhost'),
    'database' => env('DB_NAME', 'yibnyzre_cme'),
    'username' => env('DB_USER', 'yibnyzre_cme_admin'),
    'password' => env('DB_PASS', ''),
    'charset'  => 'utf8mb4',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
