<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use DateTimeImmutable;
use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Core\Shared\Utils\Collectible;
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
