<?php declare(strict_types=1);

namespace ownHackathon\Core\Repository;

use ownHackathon\Core\Entity\Token\TokenCollectionInterface;
use ownHackathon\Core\Entity\Token\TokenInterface;

interface TokenRepositoryInterface extends RepositoryInterface
{
    public function insert(TokenInterface $data): true;

    public function update(TokenInterface $data): true;

    public function findById(int $id): ?TokenInterface;

    public function findByAccountId(int $accountId): TokenCollectionInterface;

    public function findByToken(string $token): ?TokenInterface;

    public function findAll(): TokenCollectionInterface;

    public function deleteByAccountId(int $accountId): true;
}
