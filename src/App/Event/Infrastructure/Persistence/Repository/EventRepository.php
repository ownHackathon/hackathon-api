<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Infrastructure\Persistence\Repository;

use ownHackathon\App\Event\Domain\EventCollectionInterface;
use ownHackathon\App\Event\Domain\EventInterface;
use ownHackathon\App\Event\Domain\Repository\EventRepositoryInterface;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydratorInterface;
use ownHackathon\App\Event\Infrastructure\Persistence\Table\EventStoreInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;
use ownHackathon\Core\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Persistence\Store\StoreInterface;

readonly class EventRepository extends AbstractRepository implements EventRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $store,
        private EventHydratorInterface $hydrator,
    ) {
    }

    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    protected function getStore(): StoreInterface
    {
        return $this->store;
    }

    public function insert(EventInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    public function update(EventInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    public function findOneById(int $id): ?EventInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    public function findByWorkspaceId(int $workspaceId): EventCollectionInterface
    {
        $result = $this->store->fetchMany(['workspaceId' => $workspaceId]);

        return $this->mapToCollection($result);
    }

    public function findOneByName(string $name): ?EventInterface
    {
        $result = $this->store->fetchOne(['name' => $name]);

        return $this->mapToEntity($result);
    }

    public function findOneBySlug(string $slug): ?EventInterface
    {
        $result = $this->store->fetchOne(['slug' => $slug]);

        return $this->mapToEntity($result);
    }

    public function findeAll(): EventCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }
}
