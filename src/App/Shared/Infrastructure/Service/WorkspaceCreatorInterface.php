<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Service;

use Exdrals\Identity\Domain\AccountInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountInterface $owner): WorkspaceInterface;
}
