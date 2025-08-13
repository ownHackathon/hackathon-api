<?php declare(strict_types=1);

namespace ownHackathon\App\Repository;

use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Entity\Account\AccountCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Store\AccountStoreInterface;
use ownHackathon\Core\Type\Email;

readonly class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(
        private AccountStoreInterface $store,
    ) {
    }

    public function insert(AccountInterface $data): true
    {
        return $this->store->insert($data);
    }

    public function update(AccountInterface $data): true
    {
        return $this->store->update($data);
    }

    public function deleteById(int $id): true
    {
        return $this->store->deleteById($id);
    }

    public function findById(int $id): ?AccountInterface
    {
        return $this->store->findById($id);
    }

    public function findByUuid(UuidInterface $uuid): ?AccountInterface
    {
        return $this->store->findByUuid($uuid);
    }

    public function findByName(string $name): ?AccountInterface
    {
        return $this->store->findByName($name);
    }

    public function findByEmail(Email $email): ?AccountInterface
    {
        return $this->store->findByEmail($email);
    }

    public function findAll(): AccountCollectionInterface
    {
        return $this->store->findAll();
    }
}
