<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Hydrator;

use ownHackathon\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\Workspace\Domain\WorkspaceInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
