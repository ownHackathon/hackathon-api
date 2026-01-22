<?php declare(strict_types=1);

namespace App\Hydrator\Workspace;

use Core\Entity\Workspace\WorkspaceCollectionInterface;
use Core\Entity\Workspace\WorkspaceInterface;
use Core\Hydrator\HydratorInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
