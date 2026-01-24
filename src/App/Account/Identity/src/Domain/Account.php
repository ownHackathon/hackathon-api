<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use DateTimeImmutable;
use Exdrals\Mailing\Domain\EmailType;
use Ramsey\Uuid\UuidInterface;
use Exdrals\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Shared\Utils\Collectible;

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
