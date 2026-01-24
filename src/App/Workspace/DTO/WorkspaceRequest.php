<?php declare(strict_types=1);

namespace App\Workspace\DTO;

use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DataType;

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
    ) {
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }
}
