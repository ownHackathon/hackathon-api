<?php declare(strict_types=1);

namespace App\Token\DTO;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

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
