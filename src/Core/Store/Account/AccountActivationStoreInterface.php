<?php declare(strict_types=1);

namespace Core\Store\Account;

use Core\Entity\Account\AccountActivationCollectionInterface;
use Core\Entity\Account\AccountActivationInterface;
use Core\Store\StoreInterface;
use Core\Type\Email;

interface AccountActivationStoreInterface extends StoreInterface
{
    public function insert(AccountActivationInterface $data): true;

    public function update(AccountActivationInterface $data): true;

    public function findById(int $id): ?AccountActivationInterface;

    public function findByEmail(Email $email): AccountActivationCollectionInterface;

    public function findByToken(string $token): ?AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(Email $email): true;
}
