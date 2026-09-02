<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Token;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

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
