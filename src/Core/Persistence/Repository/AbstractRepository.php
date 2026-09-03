<?php declare(strict_types=1);

namespace Core\Persistence\Repository;

use Core\Persistence\Hydrator\HydratorInterface;
use Core\Persistence\Store\StoreInterface;
use Core\SharedKernel\Domain\Exception\EmptyResultException;

readonly abstract class AbstractRepository implements RepositoryInterface
{
    abstract protected function getHydrator(): HydratorInterface;

    abstract protected function getStore(): StoreInterface;

    #[\Override]
    public function deleteById(int $id): true
    {
        return $this->getStore()->remove(['id' => $id]);
    }

    protected function mapToEntity(mixed $result): mixed
    {
        if (!is_array($result)) {
            throw new EmptyResultException();
        }

        try {
            return $this->getHydrator()->hydrate($result);
        } catch (\Throwable) {
            throw new EmptyResultException();
        }
    }

    protected function mapToCollection(mixed $result): mixed
    {
        return is_array($result) ? $this->getHydrator()->hydrateCollection($result) : $this->getHydrator()->hydrateCollection([]);
    }
}
