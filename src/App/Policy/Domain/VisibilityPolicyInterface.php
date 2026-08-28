<?php declare(strict_types=1);

namespace ownHackathon\App\Policy\Domain;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;

interface VisibilityPolicyInterface
{
    public function isAvailableFor(
        VisibilityAwareInterface $element,
        ?AccountInterface $account,
    ): bool;
}
