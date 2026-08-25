<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Token;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

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
