<?php declare(strict_types=1);

namespace App\DTO\EMail;

use OpenApi\Attributes as OA;
use Core\Enum\DataType;

#[OA\Schema()]
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
