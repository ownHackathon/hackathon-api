<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use DateTimeImmutable;
use Exdrals\Mailing\Domain\EmailType;
use Ramsey\Uuid\UuidInterface;
use Exdrals\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Shared\Utils\Collectible;

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
