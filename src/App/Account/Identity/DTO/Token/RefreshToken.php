<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Token;

use ownHackathon\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['refreshToken'])]
readonly class RefreshToken
{
    public function __construct(
        #[OA\Property(
            description: 'The token after a valid log-in',
            type: DataType::STRING->value,
        )]
        public string $refreshToken,
    ) {
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }
}
