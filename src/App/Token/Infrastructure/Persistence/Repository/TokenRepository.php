<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Infrastructure\Persistence\Repository;

use ownHackathon\App\Token\Domain\Repository\TokenRepositoryInterface;
use ownHackathon\App\Token\Domain\TokenCollectionInterface;
use ownHackathon\App\Token\Domain\TokenInterface;
use ownHackathon\App\Token\Infrastructure\Hydrator\TokenHydratorInterface;
use ownHackathon\App\Token\Infrastructure\Persistence\Table\TokenStoreInterface;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;

readonly class TokenRepository extends AbstractRepository implements TokenRepositoryInterface
{
    public function __construct(
        private TokenStoreInterface $store,
        private TokenHydratorInterface $hydrator,
    ) {
    }

    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    protected function getStore(): StoreInterface
    {
        return $this->store;
    }

    public function insert(TokenInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    public function update(TokenInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    public function findOneById(int $id): ?TokenInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    public function findByAccountId(int $accountId): TokenCollectionInterface
    {
        $result = $this->store->fetchMany(['accountId' => $accountId]);

        return $this->mapToCollection($result);
    }

    public function findOneByToken(string $token): ?TokenInterface
    {
        $result = $this->store->fetchOne(['token' => $token]);

        return $this->mapToEntity($result);
    }

    public function findAll(): TokenCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    public function deleteByAccountId(int $accountId): true
    {
        return $this->store->remove(['accountId' => $accountId]);
    }
}
