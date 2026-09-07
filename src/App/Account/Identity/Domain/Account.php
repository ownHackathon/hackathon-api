<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Mailing\Api\EmailType;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class Account implements AccountInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public UuidInterface $uuid,
        public string $name,
        public string $hashedPassword,
        public EmailType $email,
        public DateTimeImmutable $registeredAt,
        public ?DateTimeImmutable $lastActionAt,
    ) {
    }

    public function withId(int $id): AccountInterface
    {
        return self::with(id: $id);
    }

    public function withUuid(UuidFactoryInterface $uuid): AccountInterface
    {
        return self::with(uuid: $uuid);
    }

    public function withName(string $name): AccountInterface
    {
        return self::with(name: $name);
    }

    public function withPasswordHash(string $hashedPassword): AccountInterface
    {
        return self::with(hashedPassword: $hashedPassword);
    }

    public function withEmail(EmailType $email): AccountInterface
    {
        return self::with(email: $email);
    }

    public function withRegisteredAt(DateTimeImmutable $registeredAt): AccountInterface
    {
        return self::with(registeredAt: $registeredAt);
    }

    public function refreshLastActionAt(): AccountInterface
    {
        return self::with(lastActionAt: new DateTimeImmutable());
    }
}
