<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Account\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountActivationInterface;
use Shared\Infrastructure\Persistence\StoreInterface;

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
