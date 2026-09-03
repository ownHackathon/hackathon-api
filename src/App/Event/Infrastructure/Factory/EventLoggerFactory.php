<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Factory;

use App\Event\Application\Port\EventLoggerInterface;
use App\Event\Infrastructure\Logger\EventLogger;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;

readonly final class EventLoggerFactory
{
    public const string CHANNEL = 'event';

    public function __invoke(ContainerInterface $container): EventLoggerInterface
    {
        $logger = (new LoggerFactory())->build($container, self::CHANNEL);

        return new EventLogger($logger);
    }
}
