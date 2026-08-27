<?php declare(strict_types=1);

namespace ownHackathon\Core\Persistence\Repository;

use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;
use ownHackathon\Core\Persistence\Store\StoreInterface;

readonly abstract class AbstractRepository implements RepositoryInterface
{
    abstract protected function getHydrator(): HydratorInterface;

    abstract protected function getStore(): StoreInterface;

    public function deleteById(int $id): true
    {
        return $this->getStore()->remove(['id' => $id]);
    }

    protected function mapToEntity(mixed $result): mixed
    {
        if (!is_array($result)) {
            return null;
        }

        try {
            return $this->getHydrator()->hydrate($result);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function mapToCollection(mixed $result): mixed
    {
        return is_array($result) ? $this->getHydrator()->hydrateCollection($result) : $this->getHydrator()->hydrateCollection([]);
    }
}
