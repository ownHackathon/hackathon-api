<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Factory;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PDO
    {
        $settings = $container->get('config')['database'];

        $dsn = $settings['driver'] === 'mysql'
            ? 'mysql:dbname=' . $settings['dbname'] . ';host=' . $settings['host'] . ';port=' . $settings['port']
            . ';charset=utf8mb4'
            : 'sqlite:' . $settings['host'];
        $user = $settings['user'];
        $password = $settings['password'];
        $options = [
            PDO::ATTR_ERRMODE => $settings['error'],
            PDO::ATTR_EMULATE_PREPARES => $settings['emulate_prepares'],
        ];

        return new PDO($dsn, $user, $password, $options);
    }
}
