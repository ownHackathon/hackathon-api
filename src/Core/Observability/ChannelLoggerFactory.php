<?php declare(strict_types=1);

namespace ownHackathon\Core\Observability;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

readonly final class ChannelLoggerFactory
{
    public const string PREFIX = 'logger.';

    public function __invoke(ContainerInterface $container, string $requestedName): LoggerInterface
    {
        $channel = str_starts_with($requestedName, self::PREFIX)
            ? substr($requestedName, strlen(self::PREFIX))
            : LoggerFactory::DEFAULT_CHANNEL;

        return new LoggerFactory()->build($container, $channel);
    }
}
