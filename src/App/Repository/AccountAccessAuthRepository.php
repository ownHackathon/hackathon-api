<?php declare(strict_types=1);

namespace ownHackathon\App\Repository;

use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\Core\Store\AccountAccessAuthStoreInterface;

readonly class AccountAccessAuthRepository implements AccountAccessAuthRepositoryInterface
{
    public function __construct(
        private AccountAccessAuthStoreInterface $store,
    ) {
    }

    public function insert(AccountAccessAuthInterface $accountAccessAuth): true
    {
        return $this->store->insert($accountAccessAuth);
    }

    public function update(AccountAccessAuthInterface $accountAccessAuth): true
    {
        return $this->store->update($accountAccessAuth);
    }

    public function deleteById(int $id): true
    {
        return $this->store->deleteById($id);
    }

    public function findById(int $id): ?AccountAccessAuthInterface
    {
        return $this->store->findById($id);
    }

    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface
    {
        return $this->store->findByAccountId($accountId);
    }

    public function findByAccountIdAndClientIdHash(int $accountId, string $clientHash): ?AccountAccessAuthInterface
    {
        return $this->store->findByAccountIdAndClientIdHash($accountId, $clientHash);
    }

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface
    {
        return $this->store->findByLabel($label);
    }

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface
    {
        return $this->store->findByRefreshToken($refreshToken);
    }

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface
    {
        return $this->store->findByUserAgent($userAgent);
    }

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface
    {
        return $this->store->findByClientIdentHash($clientIdentHash);
    }

    public function findAll(): AccountAccessAuthCollectionInterface
    {
        return $this->store->findAll();
    }
}
