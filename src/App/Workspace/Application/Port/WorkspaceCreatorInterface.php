<?php declare(strict_types=1);

namespace App\Workspace\Application\Port;

use App\Account\Identity\Domain\AccountInterface;
use App\Workspace\Domain\WorkspaceInterface;
use App\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountInterface $owner): WorkspaceInterface;
}
