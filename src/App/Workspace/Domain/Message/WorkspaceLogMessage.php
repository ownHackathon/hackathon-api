<?php declare(strict_types=1);

namespace App\Workspace\Domain\Message;

use Core\SharedKernel\Domain\Message\LogMessage;

interface WorkspaceLogMessage extends LogMessage
{
    public const string INVALID_WORKSPACE_NAME = 'Invalid workspace name';
    public const string DUPLICATED_WORKSPACE_NAME = 'workspace name already in use';
    public const string INVALID_WORKSPACE_VISIBILITY = 'Invalid workspace visibility; falling back to unlisted';
    public const string WORKSPACE_DATA_SKIPPED = 'Invalid workspace persistence data skipped';
}
