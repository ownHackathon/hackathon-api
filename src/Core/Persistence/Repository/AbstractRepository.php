<?php declare(strict_types=1);

namespace Core\Persistence\Repository;

use Core\SharedKernel\Domain\Exception\EmptyResultException;

readonly abstract class AbstractRepository implements RepositoryInterface
{
    #[\Override]
    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    protected function mapToEntity(mixed $result): mixed
    {
        if (!is_array($result)) {
            throw new EmptyResultException();
        }

        try {
            return $this->hydrator->hydrate($result);
        } catch (\Throwable) {
            throw new EmptyResultException();
        }
    }

    protected function mapToCollection(mixed $result): mixed
    {
        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }
}
