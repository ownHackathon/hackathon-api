<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Response;

use ownHackathon\Core\Serialization\DataType;
use ownHackathon\App\Account\Identity\DTO\Token\AccessToken;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['accessToken', 'refreshToken'])]
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
