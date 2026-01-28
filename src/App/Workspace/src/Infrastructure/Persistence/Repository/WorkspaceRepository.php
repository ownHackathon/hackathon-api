<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Persistence\Repository;

use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use ownHackathon\Shared\Domain\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Domain\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Shared\Domain\ValueObject\Pagination;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
use PDOException;

readonly class WorkspaceRepository implements WorkspaceRepositoryInterface
{
    public function __construct(
        private WorkspaceStoreInterface $store,
    ) {
    }

    /**
     * @throws PDOException
     * @throws DuplicateEntryException
     */
    public function insert(WorkspaceInterface $data): int
    {
        return $this->store->insert($data);
    }

    public function update(WorkspaceInterface $data): true
    {
        return $this->store->update($data);
    }

    public function deleteById(int $id): true
    {
        return $this->store->deleteById($id);
    }

    public function findById(int $id): ?WorkspaceInterface
    {
        return $this->store->findById($id);
    }

    public function findByAccountId(int $accountId, Pagination $pagination): WorkspaceCollectionInterface
    {
        return $this->store->findByAccountId($accountId, $pagination);
    }

    public function findByName(string $name): ?WorkspaceInterface
    {
        return $this->store->findByName($name);
    }

    public function findAll(): WorkspaceCollectionInterface
    {
        return $this->store->findAll();
    }

    public function countByAccount(int $accountId): int
    {
        return $this->store->countByAccount($accountId);
    }
}
