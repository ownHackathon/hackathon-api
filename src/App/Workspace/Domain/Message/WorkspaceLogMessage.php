<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Domain\Message;

use Exdrals\Core\Shared\Domain\Message\LogMessage;

interface WorkspaceLogMessage extends LogMessage
{
    public const string INVALID_WORKSPACE_NAME = 'Invalid workspace name';
    public const string DUPLICATED_WORKSPACE_NAME = 'workspace name already in use';
}
