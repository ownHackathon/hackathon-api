<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Service;

use App\Account\Identity\Api\AccountProfileInterface;
use App\Workspace\Domain\WorkspaceInterface;
use App\Workspace\DTO\WorkspaceRequest;

interface WorkspaceCreatorInterface
{
    public function create(WorkspaceRequest $workspace, AccountProfileInterface $owner): WorkspaceInterface;
}
