<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Repository;

use App\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use Core\Persistence\Repository\RepositoryInterface;

interface AccountAccessAuthRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountAccessAuthInterface $accountAccessAuth): int;

    public function update(AccountAccessAuthInterface $accountAccessAuth): true;

    public function findOneById(int $id): AccountAccessAuthInterface;

    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface;

    public function findOneByAccountIdAndClientIdHash(int $accountId, string $clientHash): AccountAccessAuthInterface;

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface;

    public function findOneByRefreshToken(string $refreshToken): AccountAccessAuthInterface;

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface;

    public function findOneByClientIdentHash(string $clientIdentHash): AccountAccessAuthInterface;

    public function findAll(): AccountAccessAuthCollectionInterface;
}
