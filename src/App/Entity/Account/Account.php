<?php declare(strict_types=1);

namespace App\Entity\Account;

use DateTimeImmutable;
use Core\Entity\Account\AccountInterface;
use Core\Trait\CloneReadonlyClassWith;
use Core\Type\Email;
use Core\Utils\Collectible;
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
