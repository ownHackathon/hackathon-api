<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Application\Port;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceInterface;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountInterface $owner): WorkspaceInterface;
}
