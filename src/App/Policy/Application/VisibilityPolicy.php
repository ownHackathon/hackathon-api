<?php declare(strict_types=1);

namespace App\Policy\Application;

use App\Account\Identity\Api\AccountProfileInterface;
use App\Policy\Api\Enum\Visibility;
use App\Policy\Api\VisibilityAwareInterface;
use App\Policy\Api\VisibilityPolicyInterface;

readonly final class VisibilityPolicy implements VisibilityPolicyInterface
{
    #[\Override]
    public function isAvailableFor(VisibilityAwareInterface $element, ?AccountProfileInterface $account): bool
    {
        return match ($element->visibility) {
            Visibility::UNLISTED => $account instanceof AccountProfileInterface
                && $account->id === $element->getOwnerId(),
            Visibility::REGISTERED => $account instanceof AccountProfileInterface,
            Visibility::PUBLIC => true,
        };
    }
}
