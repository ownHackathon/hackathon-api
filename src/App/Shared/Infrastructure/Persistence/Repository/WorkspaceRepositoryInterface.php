<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Infrastructure\Persistence\Repository;

use ownHackathon\App\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\App\Shared\Infrastructure\ValueObject\Pagination;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;

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
