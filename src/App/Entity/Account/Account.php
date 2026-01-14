<?php declare(strict_types=1);

namespace ownHackathon\App\Entity\Account;

use DateTimeImmutable;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class Account implements AccountInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public UuidInterface $uuid,
        public string $name,
        public string $password,
        public Email $email,
        public DateTimeImmutable $registeredAt,
        public DateTimeImmutable $lastActionAt,
    ) {
    }
}
