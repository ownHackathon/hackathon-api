<?php declare(strict_types=1);

namespace App\Account\Identity\DTO\Token;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['accessToken'])]
readonly final class AccessToken
{
    public function __construct(
        #[OA\Property(
            description: 'The Token for authorized access',
            type: DataType::STRING->value,
        )]
        public string $accessToken,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] string $token): self
    {
        return new self($token);
    }
}
