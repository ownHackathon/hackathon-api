<?php declare(strict_types=1);

namespace ownHackathon\Core\Token\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Shared\Domain\Enum\DataType;

#[OA\Schema()]
readonly class Token
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

    public static function fromString(?string $token): self
    {
        return new self($token);
    }
}
