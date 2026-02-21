<?php declare(strict_types=1);

namespace ownHackathon\Event\Infrastructure\Persistence\Repository;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\AbstractRepository;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;
use ownHackathon\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\Shared\Domain\Event\EventInterface;
use ownHackathon\Shared\Infrastructure\Hydrator\EventHydratorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\EventRepositoryInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Table\EventStoreInterface;

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
