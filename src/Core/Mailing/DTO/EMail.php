<?php declare(strict_types=1);

namespace ownHackathon\Core\Mailing\DTO;

use ownHackathon\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['email'])]
readonly class EMail
{
    public function __construct(
        #[OA\Property(
            description: 'The E-Mail',
            type: DataType::STRING->value,
        )]
        public string $email,
    ) {
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }
}
