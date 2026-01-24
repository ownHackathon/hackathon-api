<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

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
