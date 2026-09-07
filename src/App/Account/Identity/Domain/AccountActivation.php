<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Mailing\Api\EmailType;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class AccountActivation implements AccountActivationInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public EmailType $email,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }

    public function withId(int $id): AccountActivationInterface
    {
        return self::with(id: $id);
    }

    public function withEmail(EmailType $email): AccountActivationInterface
    {
        return self::with(email: $email);
    }

    public function withToken(UuidInterface $token): AccountActivationInterface
    {
        return self::with(token: $token);
    }

    public function withCreatedAt(DateTimeImmutable $createdAt): AccountActivationInterface
    {
        return self::with(createdAt: $createdAt);
    }
}
