<?php declare(strict_types=1);

namespace App\Event\Domain\Repository;

use App\Event\Domain\EventCollectionInterface;
use App\Event\Domain\EventInterface;
use Core\Persistence\Repository\RepositoryInterface;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function insert(EventInterface $data): int;

    public function update(EventInterface $data): true;

    public function findOneById(int $id): EventInterface;

    public function findByWorkspaceId(int $workspaceId): EventCollectionInterface;

    public function findOneByName(string $name): EventInterface;

    public function findOneBySlug(string $slug): EventInterface;

    public function findeAll(): EventCollectionInterface;
}
