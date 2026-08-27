<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;

interface WorkspaceVisibilityPolicyInterface
{
    public function isAvailableFor(
        WorkspaceInterface $workspace,
        ?AccountInterface $account,
    ): bool;
}
