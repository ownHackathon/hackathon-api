<?php declare(strict_types=1);

namespace ownHackathon\App\DTO\Account;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Enum\DataType;

#[OA\Schema(required: ['accountName', 'password'])]
readonly class AccountRegistration
{
    public function __construct(
        #[OA\Property(
            description: 'The Display Name for the Account',
            type: DataType::STRING->value,
        )]
        public string $accountName,
        #[OA\Property(
            description: 'The Password',
            type: DataType::STRING->value,
        )]
        public string $password,
    ) {
    }

    public static function fromString(string $accountName, string $password): self
    {
        return new self($accountName, $password);
    }
}
