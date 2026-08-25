<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Persistence\Repository;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;

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
        return is_array($result) ? $this->getHydrator()->hydrate($result) : null;
    }

    protected function mapToCollection(mixed $result): mixed
    {
        return is_array($result) ? $this->getHydrator()->hydrateCollection($result) : $this->getHydrator()->hydrateCollection([]);
    }
}
