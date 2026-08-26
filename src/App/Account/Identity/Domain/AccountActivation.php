<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\Core\Mailing\Domain\EmailType;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class AccountActivation implements AccountActivationInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public EmailType $email,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
