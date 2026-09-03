<?php declare(strict_types=1);

namespace App\Policy\Domain;

use App\Account\Identity\Domain\AccountInterface;
use App\Policy\Domain\Enum\Visibility;

readonly final class VisibilityPolicy implements VisibilityPolicyInterface
{
    #[\Override]
    public function isAvailableFor(VisibilityAwareInterface $element, ?AccountInterface $account): bool
    {
        return match ($element->visibility) {
            Visibility::UNLISTED => $account instanceof AccountInterface
                && $account->id === $element->getOwnerId(),
            Visibility::REGISTERED => $account instanceof AccountInterface,
            Visibility::PUBLIC => true,
        };
    }
}
