<?php declare(strict_types=1);

namespace App\Policy\Domain\Enum;

enum Visibility: int
{
    case UNLISTED = 100;
    case REGISTERED = 200;
    case PUBLIC = 300;

    public function getVisibilityName(): string
    {
        return match ($this) {
            self::UNLISTED => 'Unlisted',
            self::REGISTERED => 'Registered User',
            self::PUBLIC => 'Public',
        };
    }
}
