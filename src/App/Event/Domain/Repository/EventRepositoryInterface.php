<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Domain\Repository;

use ownHackathon\App\Event\Domain\EventCollectionInterface;
use ownHackathon\App\Event\Domain\EventInterface;
use ownHackathon\Core\Persistence\Repository\RepositoryInterface;

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
