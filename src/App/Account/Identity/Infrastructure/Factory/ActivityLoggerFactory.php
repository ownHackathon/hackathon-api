<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Factory;

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Infrastructure\Logger\ActivityLogger;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;

readonly final class ActivityLoggerFactory
{
    public const string CHANNEL = 'account-activity';

    public function __invoke(ContainerInterface $container): ActivityLoggerInterface
    {
        $logger = (new LoggerFactory())->build($container, self::CHANNEL);

        return new ActivityLogger($logger);
    }
}
