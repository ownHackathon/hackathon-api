<?php declare(strict_types=1);

return [
    'database' => [
        'driver' => 'driver',
        'host' => 'host',
        'port' => 'port',
        'user' => 'user',
        'password' => 'password',
        'dbname' => 'dbname',
        'charset' => 'utf8mb4',
        'defaultTableOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'engine' => 'InnoDB',
        ],
        'error' => PDO::ERRMODE_EXCEPTION,
    ]
];
