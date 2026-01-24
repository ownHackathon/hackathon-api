<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Response;

use Exdrals\Identity\DTO\Token\AccessToken;
use Exdrals\Identity\DTO\Token\RefreshToken;
use OpenApi\Attributes as OA;
use Exdrals\Shared\Domain\Enum\DataType;

#[OA\Schema()]
readonly class AuthenticationResponse
{
    public function __construct(
        #[OA\Property(
            description: 'The access token after a valid log-in',
            type: DataType::STRING->value,
        )]
        public string $accessToken,
        #[OA\Property(
            description: 'The refresh token after a valid log-in',
            type: DataType::STRING->value,
        )]
        public string $refreshToken,
    ) {
    }

    public static function from(AccessToken $accessToken, RefreshToken $refreshToken): self
    {
        return new self($accessToken->accessToken, $refreshToken->refreshToken);
    }
}
