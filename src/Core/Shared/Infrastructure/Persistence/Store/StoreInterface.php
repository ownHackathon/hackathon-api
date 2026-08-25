<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Persistence\Store;

use ownHackathon\Shared\Infrastructure\ValueObject\Pagination;

interface StoreInterface
{
    public function getTableName(): string;

    public function persist(array $data): int;

    public function update(int $id, array $data): true;

    public function fetchOne(array $condition): ?array;

    public function fetchMany(array $condition, ?Pagination $pagination = null): array;

    public function fetchAll(): array;

    public function count(array $condition): int;

    public function remove(array $condition): true;
}
