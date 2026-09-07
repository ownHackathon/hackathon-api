<?php declare(strict_types=1);

namespace Core\Persistence\Repository;

use Core\Persistence\Hydrator\HydratorInterface;
use Core\Persistence\Store\StoreInterface;

interface RepositoryInterface
{
    public StoreInterface $store { get; }

    public HydratorInterface $hydrator { get; }

    public function deleteById(int $id): true;
}
