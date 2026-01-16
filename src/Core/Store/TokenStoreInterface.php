<?php declare(strict_types=1);

namespace ownHackathon\Core\Store;

use ownHackathon\Core\Entity\TokenCollectionInterface;
use ownHackathon\Core\Entity\TokenInterface;

interface TokenStoreInterface extends StoreInterface
{
    public function insert(TokenInterface $data): true;

    public function update(TokenInterface $data): true;

    public function findById(int $id): ?TokenInterface;

    public function findByAccountId(int $accountId): TokenCollectionInterface;

    public function findByToken(string $token): ?TokenInterface;

    public function findAll(): TokenCollectionInterface;

    public function deleteByAccountId(int $accountId): true;
}
