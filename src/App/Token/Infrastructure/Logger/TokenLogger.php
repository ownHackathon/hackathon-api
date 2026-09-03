<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Logger;

use App\Token\Application\Port\TokenLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

readonly final class TokenLogger implements TokenLoggerInterface
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
