<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Infrastructure\Persistence\Repository;

use ownHackathon\App\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\App\Shared\Domain\Event\EventInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function insert(EventInterface $data): int;

    public function update(EventInterface $data): true;

    public function findOneById(int $id): ?EventInterface;

    public function findByWorkspaceId(int $workspaceId): EventCollectionInterface;

    public function findOneByName(string $name): ?EventInterface;

    public function findOneBySlug(string $slug): ?EventInterface;

    public function findeAll(): EventCollectionInterface;
}
