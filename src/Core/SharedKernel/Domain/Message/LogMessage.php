<?php declare(strict_types=1);

namespace ownHackathon\Core\SharedKernel\Domain\Message;

interface LogMessage
{
    public const string UNAUTHORIZED_ACCESS = 'Route was called without authentication';
}
