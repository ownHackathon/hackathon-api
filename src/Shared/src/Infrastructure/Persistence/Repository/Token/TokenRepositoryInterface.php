<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Persistence\Repository\Token;

use Exdrals\Shared\Domain\Token\TokenCollectionInterface;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;

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
