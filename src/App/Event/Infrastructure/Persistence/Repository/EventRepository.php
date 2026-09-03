<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Persistence\Repository;

use App\Event\Domain\EventCollectionInterface;
use App\Event\Domain\EventInterface;
use App\Event\Domain\Repository\EventRepositoryInterface;
use App\Event\Infrastructure\Hydrator\EventHydratorInterface;
use App\Event\Infrastructure\Persistence\Table\EventStoreInterface;
use Core\Persistence\Hydrator\HydratorInterface;
use Core\Persistence\Repository\AbstractRepository;
use Core\Persistence\Store\StoreInterface;

readonly final class EventRepository extends AbstractRepository implements EventRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $store,
        private EventHydratorInterface $hydrator,
    ) {
    }

    #[\Override]
    public function insert(EventInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    #[\Override]
    public function update(EventInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    #[\Override]
    public function findOneById(int $id): EventInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findByWorkspaceId(int $workspaceId): EventCollectionInterface
    {
        $result = $this->store->fetchMany(['workspaceId' => $workspaceId]);

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function findOneByName(string $name): EventInterface
    {
        $result = $this->store->fetchOne(['name' => $name]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findOneBySlug(string $slug): EventInterface
    {
        $result = $this->store->fetchOne(['slug' => $slug]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findeAll(): EventCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    #[\Override]
    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    #[\Override]
    protected function getStore(): StoreInterface
    {
        return $this->store;
    }
}
