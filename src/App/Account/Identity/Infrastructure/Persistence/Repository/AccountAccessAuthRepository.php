<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Repository;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\AbstractRepository;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;
use Exdrals\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;

readonly class AccountAccessAuthRepository extends AbstractRepository implements AccountAccessAuthRepositoryInterface
{
    public function __construct(
        private AccountAccessAuthStoreInterface $store,
        private AccountAccessAuthHydratorInterface $hydrator,
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

    public function insert(AccountAccessAuthInterface $accountAccessAuth): int
    {
        $data = $this->hydrator->extract($accountAccessAuth);

        return $this->store->persist($data);
    }

    public function update(AccountAccessAuthInterface $accountAccessAuth): true
    {
        $data = $this->hydrator->extract($accountAccessAuth);

        return $this->store->update($data['id'], $data);
    }

    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    public function findOneById(int $id): ?AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['accountId' => $accountId]);

        return $this->mapToCollection($result);
    }

    public function findOneByAccountIdAndClientIdHash(int $accountId, string $clientHash): ?AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne([
            'accountId' => $accountId,
            'clientHash' => $clientHash,
        ]);

        return $this->mapToEntity($result);
    }

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['label' => $label]);

        return $this->mapToCollection($result);
    }

    public function findOneByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['refreshToken' => $refreshToken]);

        return $this->mapToEntity($result);
    }

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['userAgent' => $userAgent]);

        return $this->mapToCollection($result);
    }

    public function findOneByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['ClientIdentHash' => $clientIdentHash]);

        return $this->mapToEntity($result);
    }

    public function findAll(): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }
}
