<?php declare(strict_types=1);

namespace Tests\Integration\Mock;

use Psr\Log\AbstractLogger;

final class ArrayLogger extends AbstractLogger
{
    private static array $records = [];

    public static function reset(): void
    {
        self::$records = [];
    }

    public static function all(): array
    {
        return self::$records;
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        self::$records[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }
}
