<?php declare(strict_types=1);

namespace ownHackathon\Core\Persistence\Factory;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class DatabaseFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PDO
    {
        /** @var array{database: array{driver: string, dbname: string, host: string, port: string, user: string, password: string, error: int, emulate_prepares: bool}} $config */
        $config = $container->get('config');
        $settings = $config['database'];

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
