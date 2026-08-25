<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Enum;

enum Visibility: int
{
    case PRIVATE = 1;
    case INTERNAL = 2;
    case INVITE_ONLY = 3;
    case UNLISTED = 4;
    case PUBLIC = 5;


    public function getVisibilityName(): string
    {
        return match ($this) {
            Visibility::PRIVATE => 'Private',
            Visibility::INTERNAL => 'Internal',
            Visibility::INVITE_ONLY => 'Invite only',
            Visibility::UNLISTED => 'Unlisted',
            Visibility::PUBLIC => 'Public',
        };
    }
}
