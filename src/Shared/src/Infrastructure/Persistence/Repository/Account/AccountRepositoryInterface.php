<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Persistence\Repository\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;
use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountInterface $data): int;

    public function update(AccountInterface $data): true;

    public function findById(int $id): ?AccountInterface;

    public function findByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findByName(string $name): ?AccountInterface;

    public function findByEmail(EmailType $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
