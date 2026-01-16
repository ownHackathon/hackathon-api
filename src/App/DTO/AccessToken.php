<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Message\OADataType;

#[OA\Schema()]
readonly class AccessToken
{
    public function __construct(
        #[OA\Property(
            description: 'The Token for authorized access',
            type: OADataType::STRING,
        )]
        public string $accessToken,
    ) {
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }
}
