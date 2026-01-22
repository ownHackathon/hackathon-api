<?php declare(strict_types=1);

namespace Core\Store\Account;

use Core\Entity\Account\AccountAccessAuthCollectionInterface;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Store\StoreInterface;

interface AccountAccessAuthStoreInterface extends StoreInterface
{
    public function insert(AccountAccessAuthInterface $data): true;

    public function update(AccountAccessAuthInterface $data): true;

    public function findById(int $id): ?AccountAccessAuthInterface;

    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface;

    public function findByAccountIdAndClientIdHash(int $accountId, string $clientHash): ?AccountAccessAuthInterface;

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface;

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface;

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface;

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface;

    public function findAll(): AccountAccessAuthCollectionInterface;
}
