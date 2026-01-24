<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account;

use Exdrals\Account\Identity\Domain\AccountCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountInterface;
use Exdrals\Account\Identity\Domain\Email;
use Ramsey\Uuid\UuidInterface;
use Shared\Infrastructure\Persistence\StoreInterface;

interface AccountStoreInterface extends StoreInterface
{
    public function insert(AccountInterface $data): int;

    public function update(AccountInterface $data): true;

    public function findById(int $id): ?AccountInterface;

    public function findByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findByName(string $name): ?AccountInterface;

    public function findByEmail(Email $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
