<?php declare(strict_types=1);
$filePath = __DIR__ . '/../autoload/';
if (file_exists($filePath . 'database.php')) {
    $db = (require $filePath . 'database.php')['database'];
} else {
    $db = (require $filePath . 'database.php.dist')['database'];
}

return [
    'dbname' => $db['dbname'],
    'user' => $db['user'],
    'password' => $db['password'],
    'host' => $db['host'],
    'driver' => 'pdo_mysql',
    'charset' => 'utf8mb4',
];
