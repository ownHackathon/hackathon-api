<?php declare(strict_types=1);

namespace App\Mailing\DTO;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

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
