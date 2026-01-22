<?php declare(strict_types=1);

namespace Core\Repository\Account;

use Core\Entity\Account\AccountActivationCollectionInterface;
use Core\Entity\Account\AccountActivationInterface;
use Core\Repository\RepositoryInterface;
use Core\Type\Email;

interface AccountActivationRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountActivationInterface $data): true;

    public function update(AccountActivationInterface $data): true;

    public function findById(int $id): ?AccountActivationInterface;

    public function findEmail(Email $email): AccountActivationCollectionInterface;

    public function findByToken(string $token): ?AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(Email $email): true;
}
