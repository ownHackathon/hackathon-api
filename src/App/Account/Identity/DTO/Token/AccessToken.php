<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Token;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Shared\Domain\Enum\DataType;

#[OA\Schema(required: ['accessToken'])]
readonly class AccessToken
{
    public function __construct(
        #[OA\Property(
            description: 'The Token for authorized access',
            type: DataType::STRING->value,
        )]
        public string $accessToken,
    ) {
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }
}
