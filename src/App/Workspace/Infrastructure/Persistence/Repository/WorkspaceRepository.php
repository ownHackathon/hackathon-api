<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Persistence\Repository;

use App\Workspace\Domain\WorkspaceCollectionInterface;
use App\Workspace\Domain\WorkspaceInterface;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;

readonly class WorkspaceRepository implements WorkspaceRepositoryInterface
{
    public function __construct(
        private WorkspaceStoreInterface $store,
    ) {
    }

    public function insert(WorkspaceInterface $data): true
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

    public function findByAccountId(int $accountId): WorkspaceCollectionInterface
    {
        return $this->store->findByAccountId($accountId);
    }

    public function findByName(string $name): ?WorkspaceInterface
    {
        return $this->store->findByName($name);
    }

    public function findAll(): WorkspaceCollectionInterface
    {
        return $this->store->findAll();
    }
}
