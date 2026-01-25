<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Persistence\Table;

use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use ownHackathon\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\Workspace\Domain\WorkspaceInterface;
use Exdrals\Shared\Infrastructure\Persistence\StoreInterface;
use PDOException;

interface WorkspaceStoreInterface extends StoreInterface
{
    /**
     * @throws DuplicateEntryException
     * @throws PDOException
     */
    public function insert(WorkspaceInterface $data): true;

    public function update(WorkspaceInterface $data): true;

    public function findById(int $id): ?WorkspaceInterface;

    public function findByAccountId(int $accountId): WorkspaceCollectionInterface;

    public function findByName(string $name): ?WorkspaceInterface;

    public function findAll(): WorkspaceCollectionInterface;
}
