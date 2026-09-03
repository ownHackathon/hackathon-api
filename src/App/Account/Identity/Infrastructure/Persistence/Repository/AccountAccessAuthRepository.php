<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Persistence\Repository;

use App\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use Core\Persistence\Hydrator\HydratorInterface;
use Core\Persistence\Repository\AbstractRepository;
use Core\Persistence\Store\StoreInterface;

readonly final class AccountAccessAuthRepository extends AbstractRepository implements AccountAccessAuthRepositoryInterface
{
    public function __construct(
        private AccountAccessAuthStoreInterface $store,
        private AccountAccessAuthHydratorInterface $hydrator,
    ) {
    }

    #[\Override]
    public function insert(AccountAccessAuthInterface $accountAccessAuth): int
    {
        $data = $this->hydrator->extract($accountAccessAuth);

        return $this->store->persist($data);
    }

    #[\Override]
    public function update(AccountAccessAuthInterface $accountAccessAuth): true
    {
        $data = $this->hydrator->extract($accountAccessAuth);

        return $this->store->update($data['id'], $data);
    }

    #[\Override]
    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    #[\Override]
    public function findOneById(int $id): AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['accountId' => $accountId]);

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function findOneByAccountIdAndClientIdHash(int $accountId, string $clientHash): AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne([
            'accountId' => $accountId,
            'clientHash' => $clientHash,
        ]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findByLabel(string $label): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['label' => $label]);

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function findOneByRefreshToken(string $refreshToken): AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['refreshToken' => $refreshToken]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchMany(['userAgent' => $userAgent]);

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function findOneByClientIdentHash(string $clientIdentHash): AccountAccessAuthInterface
    {
        $result = $this->store->fetchOne(['ClientIdentHash' => $clientIdentHash]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findAll(): AccountAccessAuthCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    #[\Override]
    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    #[\Override]
    protected function getStore(): StoreInterface
    {
        return $this->store;
    }
}
