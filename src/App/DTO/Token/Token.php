<?php declare(strict_types=1);

namespace App\DTO\Token;

use OpenApi\Attributes as OA;
use Core\Enum\DataType;

#[OA\Schema()]
readonly class Token
{
    public function __construct(
        #[OA\Property(
            description: 'The Token',
            type: DataType::STRING->value,
        )]
        public string $token,
    ) {
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }
}
