<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Repository;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;
use Exdrals\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Identity\Domain\AccountActivationInterface;

interface AccountActivationRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountActivationInterface $data): int;

    public function update(AccountActivationInterface $data): true;

    public function findOneById(int $id): ?AccountActivationInterface;

    public function findByEmail(EmailType $email): AccountActivationCollectionInterface;

    public function findOneByToken(string $token): ?AccountActivationInterface;

    public function findAll(): AccountActivationCollectionInterface;

    public function deleteByEmail(EmailType $email): true;
}
