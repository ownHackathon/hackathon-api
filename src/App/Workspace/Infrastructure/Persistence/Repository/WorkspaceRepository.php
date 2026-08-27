<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Persistence\Repository;

use ownHackathon\App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceInterface;
use ownHackathon\App\Workspace\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;
use ownHackathon\Core\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Persistence\Store\StoreInterface;
use ownHackathon\Core\Persistence\Pagination;
use PDOException;

readonly class WorkspaceRepository extends AbstractRepository implements WorkspaceRepositoryInterface
{
    public function __construct(
        private WorkspaceStoreInterface $store,
        private WorkspaceHydratorInterface $hydrator,
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

    /**
     * @throws PDOException
     */
    public function insert(WorkspaceInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    public function update(WorkspaceInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    public function findOneById(int $id): ?WorkspaceInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    public function findByAccountId(int $accountId, Pagination $pagination): WorkspaceCollectionInterface
    {
        $result = $this->store->fetchMany(['accountId' => $accountId], $pagination);

        return $this->mapToCollection($result);
    }

    public function findOneByName(string $name): ?WorkspaceInterface
    {
        $result = $this->store->fetchOne(['name' => $name]);

        return $this->mapToEntity($result);
    }

    public function findOneBySlug(string $slug): ?WorkspaceInterface
    {
        $result = $this->store->fetchOne(['slug' => $slug]);

        return $this->mapToEntity($result);
    }

    public function findAll(): WorkspaceCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    public function countByAccount(int $accountId): int
    {
        return $this->store->count(['accountId' => $accountId]);
    }
}
