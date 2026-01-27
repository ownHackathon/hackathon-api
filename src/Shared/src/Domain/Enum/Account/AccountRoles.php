<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Enum\Account;

enum AccountRoles: int
{
    case Owner = 1;
    case Administrator = 2;
    case Moderator = 3;
    case User = 4;
    case Guest = 5;

    public function getAccountRoleName(): string
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
