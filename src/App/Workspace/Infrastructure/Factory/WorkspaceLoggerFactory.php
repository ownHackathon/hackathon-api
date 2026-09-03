<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Factory;

use App\Workspace\Application\Port\WorkspaceLoggerInterface;
use App\Workspace\Infrastructure\Logger\WorkspaceLogger;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;

readonly final class WorkspaceLoggerFactory
{
    public const string CHANNEL = 'workspace';

    public function __invoke(ContainerInterface $container): WorkspaceLoggerInterface
    {
        $logger = (new LoggerFactory())->build($container, self::CHANNEL);

        return new WorkspaceLogger($logger);
    }
}
