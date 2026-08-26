<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository;

use ownHackathon\App\Account\Identity\Domain\AccountActivationCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\Core\Mailing\Domain\EmailType;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;

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
