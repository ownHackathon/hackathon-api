<?php declare(strict_types=1);

namespace App\Factory;

use PDO;
use Psr\Container\ContainerInterface;

readonly class DatabaseFactory
{
    public function __invoke(ContainerInterface $container): PDO
    {
        $settings = $container->get('config');
        $settings = $settings['database'];

        $dsn = $settings['driver'] === 'mysql'
            ? 'mysql:dbname=' . $settings['dbname'] . ';host=' . $settings['host'] . ';port=' . $settings['port']
            . ';charset=utf8mb4'
            : 'sqlite:' . $settings['host'];
        $user = $settings['user'];
        $password = $settings['password'];
        $options = [
            PDO::ATTR_ERRMODE => $settings['error'],
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $password, $options);
    }
}
