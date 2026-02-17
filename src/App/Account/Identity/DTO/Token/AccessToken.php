<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Token;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema()]
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
