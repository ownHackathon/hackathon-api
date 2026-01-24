<?php declare(strict_types=1);

namespace Shared\Infrastructure\Persistence;

interface StoreInterface
{
    public function getTableName(): string;

    public function deleteById(int $id): true;
}
