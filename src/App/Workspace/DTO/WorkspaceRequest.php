<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Shared\Domain\Enum\DataType;

#[OA\Schema(required: ['name', 'visibility'])]
readonly class WorkspaceRequest
{
    public function __construct(
        #[OA\Property(
            description: 'The name from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace'
        )]
        public string $name,
        #[OA\Property(
            description: 'The description from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace is wonderfully'
        )]
        public ?string $description,
        #[OA\Property(
            description: 'The details from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace is wonderfully'
        )]
        public ?string $details,
        #[OA\Property(
            description: 'The visibility from workspace',
            type: DataType::INTEGER->value,
            enum: [100, 200, 300, 400, 500, 600, 700],
            example: 700
        )]
        public int $visibility
    ) {
    }

    public static function fromArray(array $workspace): self
    {
        return new self(
            name: $workspace['name'],
            description: $workspace['description'] ?? null,
            details: $workspace['details'] ?? null,
            visibility: (int)$workspace['visibility']
        );
    }
}
