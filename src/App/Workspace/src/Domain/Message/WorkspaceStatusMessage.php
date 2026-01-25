<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Domain\Message;

use Exdrals\Shared\Domain\Message\StatusMessage;

interface WorkspaceStatusMessage extends StatusMessage
{
    public const string INVALID_WORKSPACE_NAME = 'Invalid workspace name';

    public const string DUPLICATED_WORKSPACE_NAME = 'workspace name already in use';
}
