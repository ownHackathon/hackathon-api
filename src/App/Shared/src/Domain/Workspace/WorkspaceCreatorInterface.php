<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\Workspace;

use Exdrals\Identity\Domain\AccountInterface;
use ownHackathon\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountInterface $owner): WorkspaceInterface;
}
