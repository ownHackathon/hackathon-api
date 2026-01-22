<?php declare(strict_types=1);

namespace Core\Store\Account;

use Core\Entity\Account\AccountCollectionInterface;
use Core\Entity\Account\AccountInterface;
use Core\Store\StoreInterface;
use Core\Type\Email;
use Ramsey\Uuid\UuidInterface;

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
