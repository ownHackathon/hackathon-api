<?php declare(strict_types=1);

namespace App\Account\Identity\DTO\Token;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['refreshToken'])]
readonly final class RefreshToken
{
    public function __construct(
        #[OA\Property(
            description: 'The token after a valid log-in',
            type: DataType::STRING->value,
        )]
        public string $refreshToken,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] string $token): self
    {
        return new self($token);
    }
}
