<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Mailing\Domain\EmailType;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class Account implements AccountInterface, Collectible
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
