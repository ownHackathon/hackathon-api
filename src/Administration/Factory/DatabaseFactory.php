<?php declare(strict_types=1);

namespace Administration\Factory;

use PDO;
use Psr\Container\ContainerInterface;

class DatabaseFactory
{
    public function __invoke(ContainerInterface $container): PDO
    {
        $settings = $container->get('config');
        $settings = $settings['database'];

        $dsn = 'mysql:dbname=' . $settings['name'] . ';host=' . $settings['host'] . ';charset=utf8';
        $user = $settings['user'];
        $password = $settings['password'];
        $options = [
            PDO::ATTR_ERRMODE => $settings['error'],
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $password, $options);
    }
}
