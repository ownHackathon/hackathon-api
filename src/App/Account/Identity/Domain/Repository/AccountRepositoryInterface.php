<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Repository;

use ownHackathon\App\Account\Identity\Domain\AccountCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Persistence\Repository\RepositoryInterface;
use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountInterface $data): int;

    public function update(AccountInterface $data): true;

    public function findOneById(int $id): ?AccountInterface;

    public function findOneByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findOneByName(string $name): ?AccountInterface;

    public function findOneByEmail(EmailType $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
