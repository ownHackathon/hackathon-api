<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Hydrator;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
