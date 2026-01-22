<?php declare(strict_types=1);

namespace Core\Repository\Token;

use Core\Entity\Token\TokenCollectionInterface;
use Core\Entity\Token\TokenInterface;
use Core\Repository\RepositoryInterface;

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
