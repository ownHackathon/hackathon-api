<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

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
