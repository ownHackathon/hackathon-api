<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema(required: ['workspaces'])]
readonly final class WorkspaceList
{
    public function __construct(
        #[OA\Property(
            property: 'workspaces',
            description: 'A list of workspaces',
            type: DataType::ARRAY->value,
            items: new OA\Items(ref: WorkspaceResponse::class),
        )]
        public array $workspaces,
    ) {
    }

    public static function fromArray(array $array): WorkspaceList
    {
        return new self($array);
    }
}
