<?php declare(strict_types=1);

namespace App\Workspace\DTO;

use App\Account\Identity\Domain\AccountInterface;
use App\Workspace\Domain\WorkspaceInterface;
use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['uuid', 'ownerUuid', 'name', 'slug', 'description'])]
readonly final class WorkspaceResponse
{
    public function __construct(
        #[OA\Property(
            description: 'The unique identifier of the workspace',
            type: DataType::STRING->value,
            format: 'uuid',
            example: '019becbe-f952-7b82-82fa-f41f8ae24599',
        )]
        public string $uuid,
        #[OA\Property(
            description: 'The Uuid from owner for this workspace',
            type: DataType::STRING->value,
            format: 'uuid',
            example: '019becbe-f952-7b82-82fa-f41f8ae24599',
        )]
        public string $ownerUuid,
        #[OA\Property(
            description: 'The name from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace',
        )]
        public string $name,
        #[OA\Property(
            description: 'The slug for this workspace',
            type: DataType::STRING->value,
            example: 'my-own-workspace',
        )]
        public string $slug,
        #[OA\Property(
            description: 'The desciption for this workspace',
            type: DataType::STRING->value,
            example: 'My own workspace is wonderfully',
            nullable: true,
        )]
        public ?string $description,
        public int $visibility,
    ) {
    }

    public static function fromEntity(WorkspaceInterface $workspace, AccountInterface $account): self
    {
        return new self(
            uuid: $workspace->uuid->toString(),
            ownerUuid: $account->uuid->toString(),
            name: $workspace->name,
            slug: $workspace->slug,
            description: $workspace->description,
            visibility: $workspace->visibility->value,
        );
    }
}
