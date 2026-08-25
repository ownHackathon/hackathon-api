<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use DateTimeImmutable;
use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Core\Shared\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

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
        public ?DateTimeImmutable $lastActionAt,
    ) {
    }
}
