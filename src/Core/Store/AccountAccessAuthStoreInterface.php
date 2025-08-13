<?php declare(strict_types=1);

namespace ownHackathon\Core\Store;

use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;

interface AccountAccessAuthStoreInterface extends StoreInterface
{
    public function insert(AccountAccessAuthInterface $data): true;

    public function update(AccountAccessAuthInterface $data): true;

    public function findById(int $id): ?AccountAccessAuthInterface;

    public function findByUserId(int $userId): AccountAccessAuthCollectionInterface;

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface;

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface;

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface;

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface;

    public function findAll(): AccountAccessAuthCollectionInterface;
}
