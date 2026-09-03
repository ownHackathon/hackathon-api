<?php declare(strict_types=1);

namespace App\Token\Domain\Enum;

enum TokenType: int
{
    case Default = 1;
    case EMail = 2;
}
