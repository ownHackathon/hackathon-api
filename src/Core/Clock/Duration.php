<?php declare(strict_types=1);

namespace Core\Clock;

final class Duration
{
    public const int SECOND = 1;
    public const int MINUTE = 60 * self::SECOND;
    public const int HOUR = 60 * self::MINUTE;
    public const int DAY = 24 * self::HOUR;
    public const int WEEK = 7 * self::DAY;
    public const int HALF_MINUTE = 30 * self::SECOND;
    public const int FIVE_MINUTES = 5 * self::MINUTE;
    public const int TWELVE_WEEKS = 12 * self::WEEK;
}
