<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\Token;

use ownHackathon\Core\Shared\Domain\Token\TokenCollectionInterface;
use ownHackathon\Core\Shared\Domain\Token\TokenInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;

interface TokenRepositoryInterface extends RepositoryInterface
{
    public function insert(TokenInterface $data): int;

    public function update(TokenInterface $data): true;

    public function findOneById(int $id): ?TokenInterface;

    public function findByAccountId(int $accountId): TokenCollectionInterface;

    public function findOneByToken(string $token): ?TokenInterface;

    public function findAll(): TokenCollectionInterface;

    public function deleteByAccountId(int $accountId): true;
}
