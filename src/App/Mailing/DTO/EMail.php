<?php declare(strict_types=1);

namespace ownHackathon\App\Mailing\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema(required: ['email'])]
readonly final class EMail
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
