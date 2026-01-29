<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;

#[OA\Schema()]
readonly class WorkspaceResponse
{
    public function __construct(
        #[OA\Property(
            description: 'The unique identifier of the workspace',
            type: DataType::STRING->value,
            example: '019becbe-f952-7b82-82fa-f41f8ae24599'
        )]
        public string $uuid,
        #[OA\Property(
            description: 'The Uuid from owner for this workspace',
            type: DataType::STRING->value,
            example: '019becbe-f952-7b82-82fa-f41f8ae24599'
        )]
        public string $ownerUuid,
        #[OA\Property(
            description: 'The name from workspace',
            type: DataType::STRING->value,
            example: 'My own workspace'
        )]
        public string $name,
        #[OA\Property(
            description: 'The slug for this workspace',
            type: DataType::STRING->value,
            example: 'my-own-workspace'
        )]
        public string $slug,
        #[OA\Property(
            description: 'The desciption for this workspace',
            type: DataType::STRING->value,
            example: 'My own workspace is wonderfully'
        )]
        public ?string $description,
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
        );
    }
}
