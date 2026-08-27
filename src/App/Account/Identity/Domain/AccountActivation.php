<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\SharedKernel\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\SharedKernel\Utils\Collectible;
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
