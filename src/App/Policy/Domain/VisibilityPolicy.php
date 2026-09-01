<?php declare(strict_types=1);

namespace ownHackathon\App\Policy\Domain;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Policy\Domain\Enum\Visibility;

readonly class VisibilityPolicy implements VisibilityPolicyInterface
{
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
