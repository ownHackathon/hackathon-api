<?php declare(strict_types=1);

namespace App\DTO\Workspace;

use Core\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['name'])]
readonly class Workspace
{
    public function __construct(
        #[OA\Property(
            description: 'The name from workspace',
            type: DataType::STRING->value
        )]
        public string $name,
    ) {
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }
}
