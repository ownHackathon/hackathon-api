<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

use Exdrals\Account\Identity\DTO\Account\Account;
use ownHackathon\Workspace\Domain\Workspace;
use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DataType;

#[OA\Schema()]
readonly class WorkspaceResponse
{
    public function __construct(
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
    ) {
    }

    public static function fromEntity(Workspace $workspace, Account $account): self
    {
        return new self(
            ownerUuid: $account->uuid,
            name: $workspace->name,
            slug: $workspace->slug,
        );
    }
}
