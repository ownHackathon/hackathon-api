<?php declare(strict_types=1);

namespace App\Repository\Account;

use Core\Entity\Account\AccountActivationCollectionInterface;
use Core\Entity\Account\AccountActivationInterface;
use Core\Repository\Account\AccountActivationRepositoryInterface;
use Core\Store\Account\AccountActivationStoreInterface;
use Core\Type\Email;

readonly class AccountActivationRepository implements AccountActivationRepositoryInterface
{
    public function __construct(
        private AccountActivationStoreInterface $store,
    ) {
    }

    public function insert(AccountActivationInterface $data): true
    {
        return $this->store->insert($data);
    }

    public function update(AccountActivationInterface $data): true
    {
        return $this->store->update($data);
    }

    public function findById(int $id): ?AccountActivationInterface
    {
        return $this->store->findById($id);
    }

    public function findEmail(Email $email): AccountActivationCollectionInterface
    {
        return $this->store->findByEmail($email);
    }

    public function findByToken(string $token): ?AccountActivationInterface
    {
        return $this->store->findByToken($token);
    }

    public function findAll(): AccountActivationCollectionInterface
    {
        return $this->store->findAll();
    }

    public function deleteById(int $id): true
    {
        return $this->store->deleteById($id);
    }

    public function deleteByEmail(Email $email): true
    {
        return $this->store->deleteByEmail($email);
    }
}
