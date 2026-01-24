<?php declare(strict_types=1);

namespace Shared\Infrastructure\Persistence;

interface RepositoryInterface
{
    public function deleteById(int $id): true;
}
