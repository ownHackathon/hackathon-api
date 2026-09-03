<?php declare(strict_types=1);

namespace App\Token\Domain\Repository;

use App\Token\Domain\TokenCollectionInterface;
use App\Token\Domain\TokenInterface;
use Core\Persistence\Repository\RepositoryInterface;

interface TokenRepositoryInterface extends RepositoryInterface
{
    public function insert(TokenInterface $data): int;

    public function update(TokenInterface $data): true;

    public function findOneById(int $id): TokenInterface;

    public function findByAccountId(int $accountId): TokenCollectionInterface;

    public function findOneByToken(string $token): TokenInterface;

    public function findAll(): TokenCollectionInterface;

    public function deleteByAccountId(int $accountId): true;
}
