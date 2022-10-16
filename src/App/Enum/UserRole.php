<?php declare(strict_types=1);

namespace App\Enum;

enum UserRole: int
{
    case OWNER = 1;
    case ADMINISTRATOR = 2;
    case MODERATOR = 3;
    case USER = 4;
}
