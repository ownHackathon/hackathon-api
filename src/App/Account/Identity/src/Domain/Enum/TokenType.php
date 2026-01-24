<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain\Enum;

enum TokenType: int
{
    case Default = 1;
    case EMail = 2;
}
