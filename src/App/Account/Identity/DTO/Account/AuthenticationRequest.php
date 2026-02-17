<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Account;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

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
