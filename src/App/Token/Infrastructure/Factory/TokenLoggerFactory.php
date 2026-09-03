<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Factory;

use App\Token\Application\Port\TokenLoggerInterface;
use App\Token\Infrastructure\Logger\TokenLogger;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;

readonly final class TokenLoggerFactory
{
    public const string CHANNEL = 'token';

    public function __invoke(ContainerInterface $container): TokenLoggerInterface
    {
        $logger = (new LoggerFactory())->build($container, self::CHANNEL);

        return new TokenLogger($logger);
    }
}
