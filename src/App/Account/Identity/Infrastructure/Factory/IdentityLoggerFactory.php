<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Factory;

use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Logger\IdentityLogger;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;

readonly final class IdentityLoggerFactory
{
    public const string CHANNEL = 'identity';

    public function __invoke(ContainerInterface $container): IdentityLoggerInterface
    {
        $logger = (new LoggerFactory())->build($container, self::CHANNEL);

        return new IdentityLogger($logger);
    }
}
