<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Message\OADataType;

#[OA\Schema(required: ['accountName', 'password'])]
readonly class AccountRegistrationDTO
{
    public function __construct(
        #[OA\Property(
            description: 'The Display Name for the Account',
            type: OADataType::STRING,
        )]
        public string $accountName,
        #[OA\Property(
            description: 'The Password',
            type: OADataType::STRING,
        )]
        public string $password,
    ) {
    }

    public static function fromString(string $accountName, string $password): self
    {
        return new self($accountName, $password);
    }
}
