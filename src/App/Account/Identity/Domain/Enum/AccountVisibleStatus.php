<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Enum;

enum AccountVisibleStatus: int
{
    case ONLINE = 1;
    case NOT_PRESENT = 2;
    case DO_NOT_DISTURB = 3;
    case GHOST = 4;
    case PERSONALIZED = 5;

    public function getVisibleStatusName(): string
    {
        return match ($this) {
            AccountVisibleStatus::ONLINE => 'online',
            AccountVisibleStatus::NOT_PRESENT => 'not present',
            AccountVisibleStatus::DO_NOT_DISTURB => 'do not-disturb',
            AccountVisibleStatus::GHOST => 'ghost',
            AccountVisibleStatus::PERSONALIZED => 'personalized',
        };
    }
}
