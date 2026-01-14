<?php declare(strict_types=1);

namespace ownHackathon\App\DTO\Response;

use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\Token\AccessToken;
use ownHackathon\App\DTO\Token\RefreshToken;
use ownHackathon\Core\Enum\DataType;

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
