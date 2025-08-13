<?php declare(strict_types=1);

namespace ownHackathon\Core\Store;

use ownHackathon\Core\Entity\Account\AccountActivationCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Type\Email;

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
