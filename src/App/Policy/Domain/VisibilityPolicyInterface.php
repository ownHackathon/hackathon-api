<?php declare(strict_types=1);

namespace App\Policy\Domain;

use App\Account\Identity\Domain\AccountInterface;

interface VisibilityPolicyInterface
{
    public function isAvailableFor(
        VisibilityAwareInterface $element,
        ?AccountInterface $account,
    ): bool;
}
