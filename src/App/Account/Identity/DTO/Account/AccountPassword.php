<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Account;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema(required: ['password'])]
readonly final class AccountPassword
{
    public function __construct(
        #[OA\Property(
            description: 'The Password',
            type: DataType::STRING->value,
        )]
        public string $password,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] string $password): self
    {
        return new self($password);
    }
}
