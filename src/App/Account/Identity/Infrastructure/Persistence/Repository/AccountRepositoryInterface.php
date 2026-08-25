<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Repository;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\RepositoryInterface;
use Exdrals\Identity\Domain\AccountCollectionInterface;
use Exdrals\Identity\Domain\AccountInterface;
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
