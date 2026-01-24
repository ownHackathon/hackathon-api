<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Hydrator\Workspace;

use App\Workspace\Domain\WorkspaceCollectionInterface;
use App\Workspace\Domain\WorkspaceInterface;
use Shared\Infrastructure\Hydrator\HydratorInterface;

interface WorkspaceHydratorInterface extends HydratorInterface
{
    public function extract(WorkspaceInterface $object): array;

    public function extractCollection(WorkspaceCollectionInterface $collection): array;
}
