<?php declare(strict_types=1);

return [
    'database' => [
        'driver' => 'sqlite',
        'host' => __DIR__ . '/../../database/database.sqlite',
        'port' => '3306',
        'user' => 'dev',
        'password' => 'dev',
        'dbname' => 'db',
        'charset' => 'utf8mb4',
        'error' => PDO::ERRMODE_EXCEPTION,
    ]
];
