<?php declare(strict_types=1);

namespace ownHackathon\Core\Repository;

interface RepositoryInterface
{
    public function deleteById(int $id): true;
}
