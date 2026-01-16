<?php declare(strict_types=1);

namespace ownHackathon\Core\Repository;

use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;

interface AccountAccessAuthRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountAccessAuthInterface $accountAccessAuth): true;

    public function update(AccountAccessAuthInterface $accountAccessAuth): true;

    public function findById(int $id): ?AccountAccessAuthInterface;

    public function findByUserId(int $userId): AccountAccessAuthCollectionInterface;

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface;

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface;

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface;

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface;

    public function findAll(): AccountAccessAuthCollectionInterface;
}
