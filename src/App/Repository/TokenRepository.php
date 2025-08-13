<?php declare(strict_types=1);

namespace ownHackathon\App\Repository;

use ownHackathon\Core\Entity\TokenCollectionInterface;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
use ownHackathon\Core\Store\TokenStoreInterface;

readonly class TokenRepository implements TokenRepositoryInterface
{
    public function __construct(
        private TokenStoreInterface $store,
    ) {
    }

    public function insert(TokenInterface $data): true
    {
        return $this->store->insert($data);
    }

    public function update(TokenInterface $data): true
    {
        return $this->store->update($data);
    }

    public function findById(int $id): ?TokenInterface
    {
        return $this->store->findById($id);
    }

    public function findByAccountId(int $accountId): TokenCollectionInterface
    {
        return $this->store->findByAccountId($accountId);
    }

    public function findByToken(string $token): ?TokenInterface
    {
        return $this->store->findByToken($token);
    }

    public function findAll(): TokenCollectionInterface
    {
        return $this->store->findAll();
    }

    public function deleteById(int $id): true
    {
        return $this->store->deleteById($id);
    }

    public function deleteByAccountId(int $accountId): true
    {
        return $this->store->deleteByAccountId($accountId);
    }
}
