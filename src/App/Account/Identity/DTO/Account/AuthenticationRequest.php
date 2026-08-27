<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Account;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema(required: ['email', 'password'])]
readonly class AuthenticationRequest
{
    public function __construct(
        #[OA\Property(
            description: 'The E-Mail from Account',
            type: DataType::STRING->value,
        )]
        public string $email,
        #[OA\Property(
            description: 'The Password from Account',
            type: DataType::STRING->value,
        )]
        public string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['email'] ?? '',
            $data['password'] ?? '',
        );
    }
}
