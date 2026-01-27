<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Repository\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountActivationCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountActivationInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountActivationStoreInterface;

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

    public function findEmail(EmailType $email): AccountActivationCollectionInterface
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

    public function deleteByEmail(EmailType $email): true
    {
        return $this->store->deleteByEmail($email);
    }
}
