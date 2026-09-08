<?php declare(strict_types=1);

namespace App\Policy\Api;

use App\Account\Identity\Api\AccountProfileInterface;

interface VisibilityPolicyInterface
{
    public function isAvailableFor(
        VisibilityAwareInterface $element,
        ?AccountProfileInterface $account,
    ): bool;
}
