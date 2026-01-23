<?php declare(strict_types=1);

namespace App\DTO\Account;

use Core\Entity\Account\AccountInterface;
use Core\Enum\DateTimeFormat;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class Account
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $email,
        public string $registeredAt,
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
