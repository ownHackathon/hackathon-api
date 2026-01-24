<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Persistence\Table\Token;

use Exdrals\Account\Identity\Domain\TokenCollectionInterface;
use Exdrals\Account\Identity\Domain\TokenInterface;
use Shared\Infrastructure\Persistence\StoreInterface;

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
