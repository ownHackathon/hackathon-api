<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Table\Account;

use Exdrals\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Shared\Infrastructure\Persistence\StoreInterface;

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
