<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Repository;

use App\Account\Identity\Domain\AccountActivationCollectionInterface;
use App\Account\Identity\Domain\AccountActivationInterface;
use App\Mailing\Domain\EmailType;
use Core\Persistence\Repository\RepositoryInterface;

interface AccountActivationRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountActivationInterface $data): int;

    public function update(AccountActivationInterface $data): true;

    public function findOneById(int $id): AccountActivationInterface;

    public function findByEmail(EmailType $email): AccountActivationCollectionInterface;

    public function findOneByToken(string $token): AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(EmailType $email): true;
}
