<?php declare(strict_types=1);

namespace Core\Store;

interface StoreInterface
{
    public function getTableName(): string;

    public function deleteById(int $id): true;
}
