<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Hydrator\Workspace;

use App\Workspace\Domain\Workspace;
use App\Workspace\Domain\WorkspaceCollection;
use App\Workspace\Domain\WorkspaceCollectionInterface;
use App\Workspace\Domain\WorkspaceInterface;
use Exception;

readonly class WorkspaceHydrator implements WorkspaceHydratorInterface
{
    /**
     * @throws Exception
     */
    public function hydrate(array $data): WorkspaceInterface
    {
        return new Workspace(
            id: $data['id'],
            accountId: $data['accountId'],
            name: $data['name'],
            slug: $data['slug'],
        );
    }

    /**
     * @throws Exception
     */
    public function hydrateCollection(array $data): WorkspaceCollectionInterface
    {
        $collection = new WorkspaceCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    public function extract(WorkspaceInterface $object): array
    {
        return [
            'id' => $object->id,
            'accountId' => $object->accountId,
            'name' => $object->name,
            'slug' => $object->slug,
        ];
    }

    public function extractCollection(WorkspaceCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
