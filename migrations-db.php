<?php declare(strict_types=1);
$db = (require __DIR__ . '/config/database.php')['database'];
return [
    'dbname' => $db['dbname'],
    'user' => $db['user'],
    'password' => $db['password'],
    'host' => $db['host'],
    'driver' => 'pdo_mysql',
    'charset' => 'utf8mb4',
];
