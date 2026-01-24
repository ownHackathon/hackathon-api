<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

use OpenApi\Attributes as OA;
use Exdrals\Shared\Domain\Enum\DataType;

#[OA\Schema(required: ['name'])]
readonly class WorkspaceRequest
{
    public function __construct(
        #[OA\Property(
            description: 'The name from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace'
        )]
        public string $workspaceName,
    ) {
    }

    public static function fromString(string $workspaceName): self
    {
        return new self($workspaceName);
    }
}
