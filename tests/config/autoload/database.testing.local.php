<?php declare(strict_types=1);

return [
    'database' => [
        'database' => 'sqlite',
        'host' => __DIR__ . '/../../../database/database.sqlite',
        'port' => '3306',
        'user' => 'dev',
        'password' => 'dev',
        'dbname' => 'db',
        'error' => PDO::ERRMODE_EXCEPTION,
    ]
];
