<?php declare(strict_types=1);

namespace App\Repository;

interface Repository
{
    public function getTableName(): string;

    public function findById(int $id): array;

    public function findAll(): array;
}
