<?php declare(strict_types=1);

namespace Core\Store\Workspace;

use Core\Entity\Workspace\WorkspaceCollectionInterface;
use Core\Entity\Workspace\WorkspaceInterface;
use Core\Store\StoreInterface;

interface WorkspaceStoreInterface extends StoreInterface
{
    public function insert(WorkspaceInterface $data): true;

    public function update(WorkspaceInterface $data): true;

    public function findById(int $id): ?WorkspaceInterface;

    public function findByAccountId(int $accountId): WorkspaceCollectionInterface;

    public function findByName(string $name): ?WorkspaceInterface;

    public function findAll(): WorkspaceCollectionInterface;
}
