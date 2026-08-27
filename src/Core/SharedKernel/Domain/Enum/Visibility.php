<?php declare(strict_types=1);

namespace ownHackathon\Core\SharedKernel\Domain\Enum;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;

enum Visibility: int
{
    case UNLISTED = 100;
    case REGISTERED = 200;
    case PUBLIC = 300;

    public function getVisibilityName(): string
    {
        return match ($this) {
            self::UNLISTED => 'Unlisted',
            self::REGISTERED => 'Registered User',
            self::PUBLIC => 'Public',
        };
    }

    public function isVisibleTo(?AccountInterface $account, bool $isOwner): bool
    {
        return match ($this) {
            self::PUBLIC => true,
            self::REGISTERED => $account instanceof AccountInterface,
            self::UNLISTED => $isOwner,
        };
    }
}
