<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Hydrator;

use ownHackathon\App\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): WorkspaceInterface;

    public function hydrateCollection(array $data): WorkspaceCollectionInterface;

    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
