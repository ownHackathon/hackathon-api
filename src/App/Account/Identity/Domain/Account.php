<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\Core\Mailing\Domain\EmailType;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;
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
