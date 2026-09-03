<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Logger;

use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

readonly final class IdentityLogger implements IdentityLoggerInterface
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
