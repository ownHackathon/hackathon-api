<?php declare(strict_types=1);

namespace ownHackathon\App\Token\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema()]
readonly final class Token
{
    public function __construct(
        #[OA\Property(
            description: 'The Token',
            type: DataType::STRING->value,
            nullable: true,
        )]
        public ?string $token,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] ?string $token): self
    {
        return new self($token);
    }
}
