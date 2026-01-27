<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Persistence\Store\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountActivationCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountActivationInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\StoreInterface;

interface AccountActivationStoreInterface extends StoreInterface
{
    public function insert(AccountActivationInterface $data): true;

    public function update(AccountActivationInterface $data): true;

    public function findById(int $id): ?AccountActivationInterface;

    public function findByEmail(EmailType $email): AccountActivationCollectionInterface;

    public function findByToken(string $token): ?AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(EmailType $email): true;
}
