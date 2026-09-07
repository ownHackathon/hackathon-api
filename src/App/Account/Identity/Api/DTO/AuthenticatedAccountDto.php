<?php declare(strict_types=1);

namespace App\Account\Identity\Api\DTO;

use App\Mailing\Api\EmailType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class AuthenticatedAccountDto
{
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
}
