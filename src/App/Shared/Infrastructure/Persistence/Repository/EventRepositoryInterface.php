<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Persistence\Repository;

use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;
use ownHackathon\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\Shared\Domain\Event\EventInterface;

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
