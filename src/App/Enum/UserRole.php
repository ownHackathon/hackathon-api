<?php declare(strict_types=1);

namespace App\Enum;

enum UserRole: int
{
    case GUEST = 0;
    case USER = 1;
    case MODERATOR = 100;
    case ADMINISTRATOR = 1000;
    case OWNER = 999999;

    public function getRoleName(): string
    {
        return match ($this) {
            UserRole::OWNER => 'Owner',
            UserRole::ADMINISTRATOR => 'Administrator',
            UserRole::MODERATOR => 'Moderator',
            UserRole::USER => 'User',
            UserRole::GUEST => 'Guest'
        };
    }
}
