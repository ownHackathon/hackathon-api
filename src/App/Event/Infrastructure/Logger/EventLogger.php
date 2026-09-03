<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Logger;

use App\Event\Application\Port\EventLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

readonly final class EventLogger implements EventLoggerInterface
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
