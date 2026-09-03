<?php declare(strict_types=1);

namespace Core\Observability;

final class CorrelationIdRegistry
{
    private static string $correlationId = '';

    public static function set(string $correlationId): void
    {
        self::$correlationId = $correlationId;
    }

    public static function get(): string
    {
        return self::$correlationId;
    }

    public static function clear(): void
    {
        self::$correlationId = '';
    }

    public static function has(): bool
    {
        return self::$correlationId !== '';
    }
}
