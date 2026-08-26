<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Domain\Enum\Token;

enum TokenType: int
{
    case Default = 1;
    case EMail = 2;
}
