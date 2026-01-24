<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Persistence\Table;

use App\Workspace\Domain\WorkspaceCollectionInterface;
use App\Workspace\Domain\WorkspaceInterface;
use Shared\Infrastructure\Persistence\StoreInterface;

interface WorkspaceStoreInterface extends StoreInterface
{
    public function insert(WorkspaceInterface $data): true;

    public function update(WorkspaceInterface $data): true;

    public function findById(int $id): ?WorkspaceInterface;

    public function findByAccountId(int $accountId): WorkspaceCollectionInterface;

    public function findByName(string $name): ?WorkspaceInterface;

    public function findAll(): WorkspaceCollectionInterface;
}
