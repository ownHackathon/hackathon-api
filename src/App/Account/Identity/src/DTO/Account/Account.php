<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Account;

use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Enum\DataType;
use Exdrals\Shared\Domain\Enum\DateTimeFormat;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Detailed information about a user account'
)]
readonly class Account
{
    public function __construct(
        #[OA\Property(
            description: 'The unique identifier of the account',
            format: 'uuid',
            example: '019becbe-f952-7b82-82fa-f41f8ae24599'
        )]
        public string $uuid,
        #[OA\Property(
            description: 'The display name of the account holder',
            example: 'Max Mustermann'
        )]
        public string $name,
        #[OA\Property(
            description: 'The email address associated with the account',
            format: 'email',
            example: 'max.mustermann@example.com'
        )]
        public string $email,
        #[OA\Property(
            description: 'The timestamp when the account was registered',
            type: DataType::STRING->value,
            format: DateTimeFormat::DEFAULT->value,
            example: '2024-01-20 14:30:00'
        )]
        public string $registeredAt,
        #[OA\Property(
            description: 'The timestamp of the last activity on this account',
            type: DataType::STRING->value,
            format: DateTimeFormat::DEFAULT->value,
            example: '2024-05-15 10:00:00'
        )]
        public string $lastActionAt,
    ) {
    }

    public static function createFromAccount(AccountInterface $account): self
    {
        return new self(
            uuid: $account->uuid->toString(),
            name: $account->name,
            email: $account->email->toString(),
            registeredAt: $account->registeredAt->format(DateTimeFormat::DEFAULT->value),
            lastActionAt: $account->lastActionAt->format(DateTimeFormat::DEFAULT->value),
        );
    }
}
