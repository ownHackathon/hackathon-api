<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Domain\Message;

use ownHackathon\Core\SharedKernel\Domain\Message\LogMessage;

interface EventLogMessage extends LogMessage
{
    public const string INVALID_EVENT_VISIBILITY = 'Invalid event visibility; falling back to unlisted';
    public const string EVENT_DATA_SKIPPED = 'Invalid event persistence data skipped';
}
