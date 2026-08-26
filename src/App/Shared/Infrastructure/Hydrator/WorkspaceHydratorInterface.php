<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Infrastructure\Hydrator;

use ownHackathon\App\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): WorkspaceInterface;

    public function hydrateCollection(array $data): WorkspaceCollectionInterface;

    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
