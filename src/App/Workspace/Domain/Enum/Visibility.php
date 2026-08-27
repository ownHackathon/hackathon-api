<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain\Enum;

enum Visibility: int
{
    case PRIVATE = 100;
    case INTERNAL = 200;
    case FRIENDS_ONLY = 300;
    case INVITE_ONLY = 400;
    case REGISTERED = 500;
    case UNLISTED = 600;
    case PUBLIC = 700;

    public function getVisibilityName(): string
    {
        return match ($this) {
            self::PRIVATE => 'Private',
            self::INTERNAL => 'Internal',
            self::FRIENDS_ONLY => 'Friends only',
            self::INVITE_ONLY => 'Invite only',
            self::REGISTERED => 'Registered User',
            self::UNLISTED => 'Unlisted',
            self::PUBLIC => 'Public',
        };
    }

    public function isAtLeast(Visibility $visibility): bool
    {
        return $this->value >= $visibility->value;
    }
}
