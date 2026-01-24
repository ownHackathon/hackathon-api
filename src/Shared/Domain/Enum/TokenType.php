<?php declare(strict_types=1);

namespace Shared\Domain\Enum;

enum TokenType: int
{
    case Default = 1;
    case EMail = 2;
}
