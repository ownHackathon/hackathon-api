<?php declare(strict_types=1);

namespace ownHackathon\App\Entity\Account;

use DateTimeImmutable;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class AccountActivation implements AccountActivationInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public Email $email,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
