<?php declare(strict_types=1);

namespace ownHackathon\Core\Token\Duse Exdrals\Core\Shared\Domain\Enum\DataType;use OpenApi\Attributes as OA;

s as OA;

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
