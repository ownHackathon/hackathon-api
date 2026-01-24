<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\DTO\Token;

use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DataType;

#[OA\Schema()]
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
