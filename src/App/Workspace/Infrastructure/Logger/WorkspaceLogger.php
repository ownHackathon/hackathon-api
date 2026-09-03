<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Logger;

use App\Workspace\Application\Port\WorkspaceLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

readonly final class WorkspaceLogger implements WorkspaceLoggerInterface
{
    use LoggerTrait;

    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
