<?php declare(strict_types=1);

namespace ownHackathon\Core\Enum;

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
            AccountVisibleStatus::NOT_PRESENT => 'Abwesend',
            AccountVisibleStatus::DO_NOT_DISTURB => 'Bitte nicht stören',
            AccountVisibleStatus::GHOST => 'unsichtbar',
            AccountVisibleStatus::PERSONALIZED => 'personalisiert'
        };
    }
}
