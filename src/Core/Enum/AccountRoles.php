<?php declare(strict_types=1);

namespace ownHackathon\Core\Enum;

enum AccountRoles: int
{
    case Owner = 1;
    case Administrator = 2;
    case Moderator = 3;
    case User = 4;
    case Guest = 5;

    public function getVisibleStatusName(): string
    {
        return match ($this) {
            AccountRoles::Owner => 'Eigentümer',
            AccountRoles::Administrator => 'Administrator',
            AccountRoles::Moderator => 'Moderator',
            AccountRoles::User => 'Benutzer',
            AccountRoles::Guest => 'Gast'
        };
    }
}
