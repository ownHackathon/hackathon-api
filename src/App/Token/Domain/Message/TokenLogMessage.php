<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Domain\Message;

use ownHackathon\Core\SharedKernel\Domain\Message\LogMessage;

interface TokenLogMessage extends LogMessage
{
    public const string TOKEN_DATA_SKIPPED = 'Invalid token persistence data skipped';
}
