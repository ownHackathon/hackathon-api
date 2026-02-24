<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Hydrator;

use Exception;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\Shared\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\Workspace\Domain\Workspace;
use ownHackathon\Workspace\Domain\WorkspaceCollection;

readonly class WorkspaceHydrator implements WorkspaceHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
    ) {
    }

    /**
     * @throws Exception
     */
    public function hydrate(array $data): WorkspaceInterface
    {
        return new Workspace(
            id: $data['id'],
            uuid: $this->uuid->fromString($data['uuid']),
            accountId: $data['accountId'],
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'],
            details: $data['details'],
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
            'uuid' => $object->uuid->toString(),
            'accountId' => $object->accountId,
            'name' => $object->name,
            'slug' => $object->slug,
            'description' => $object->description,
            'details' => $object->details,
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
