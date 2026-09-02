<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\SharedKernel\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\SharedKernel\Utils\Collectible;
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
