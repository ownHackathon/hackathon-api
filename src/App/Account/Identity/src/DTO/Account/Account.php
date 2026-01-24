<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\DTO\Account;

use Exdrals\Account\Identity\Domain\AccountInterface;
use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DateTimeFormat;

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
