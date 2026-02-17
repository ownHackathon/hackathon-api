<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Persistence\Repository;

use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\Shared\Infrastructure\ValueObject\Pagination;

interface WorkspaceRepositoryInterface extends RepositoryInterface
{
    public function insert(WorkspaceInterface $data): int;

    public function update(WorkspaceInterface $data): true;

    public function findById(int $id): ?WorkspaceInterface;

    public function findByAccountId(int $accountId, Pagination $pagination): WorkspaceCollectionInterface;

    public function findByName(string $name): ?WorkspaceInterface;

    public function findAll(): WorkspaceCollectionInterface;

    public function countByAccount(int $accountId): int;
}
