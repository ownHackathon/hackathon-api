<?php declare(strict_types=1);

namespace App\Token\Api\DTO;

use App\Token\Api\Enum\TokenType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class PasswordChangeTokenDto
{
    public function __construct(
        public ?int $id,
        public int $accountId,
        public TokenType $tokenType,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
