<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['name'])]
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
    ) {
    }

    public static function fromArray(array $workspace): self
    {
        return new self(
            $workspace['name'],
            $workspace['description'],
        );
    }
}
