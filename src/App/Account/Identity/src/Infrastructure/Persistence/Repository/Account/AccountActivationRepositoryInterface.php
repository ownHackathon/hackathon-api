<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Repository\Account;

use Exdrals\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Identity\Domain\AccountActivationInterface;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Infrastructure\Persistence\RepositoryInterface;

interface AccountActivationRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountActivationInterface $data): true;

    public function update(AccountActivationInterface $data): true;

    public function findById(int $id): ?AccountActivationInterface;

    public function findEmail(EmailType $email): AccountActivationCollectionInterface;

    public function findByToken(string $token): ?AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(EmailType $email): true;
}
