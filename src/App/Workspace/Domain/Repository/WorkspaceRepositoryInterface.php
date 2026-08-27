<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain\Repository;

use ownHackathon\App\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceInterface;
use ownHackathon\Core\Persistence\Repository\RepositoryInterface;
use ownHackathon\Core\Persistence\Pagination;

interface WorkspaceRepositoryInterface extends RepositoryInterface
{
    public function insert(WorkspaceInterface $data): int;

    public function update(WorkspaceInterface $data): true;

    public function findOneById(int $id): ?WorkspaceInterface;

    public function findByAccountId(int $accountId, Pagination $pagination): WorkspaceCollectionInterface;

    public function findOneByName(string $name): ?WorkspaceInterface;

    public function findOneBySlug(string $slug): ?WorkspaceInterface;

    public function findAll(): WorkspaceCollectionInterface;

    public function countByAccount(int $accountId): int;
}
