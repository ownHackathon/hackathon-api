<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

readonly class Account implements AccountInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public UuidInterface $uuid,
        public string $name,
        public string $password,
        public EmailType $email,
        public DateTimeImmutable $registeredAt,
        public DateTimeImmutable $lastActionAt,
    ) {
    }
}
