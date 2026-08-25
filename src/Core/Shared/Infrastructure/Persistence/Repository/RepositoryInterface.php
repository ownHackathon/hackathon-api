<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Persistence\Repository;

interface RepositoryInterface
{
    public function deleteById(int $id): true;
}
