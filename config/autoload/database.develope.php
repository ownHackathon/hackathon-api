<?php declare(strict_types=1);

return [
    'database' => [
        'driver' => 'mysql',
        'host' => 'database',
        'port' => '3306',
        'user' => 'dev',
        'password' => 'dev',
        'dbname' => 'db',
        'charset' => 'utf8mb4',
        'defaultTableOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'engine' => 'InnoDB',
        ],
        'error' => PDO::ERRMODE_EXCEPTION,
        'emulate_prepares' => false,
    ]
];
