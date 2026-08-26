<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Infrastructure\Service;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountInterface $owner): WorkspaceInterface;
}
