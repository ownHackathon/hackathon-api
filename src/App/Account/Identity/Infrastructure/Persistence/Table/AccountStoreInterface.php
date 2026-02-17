<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Table;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;
use Exdrals\Identity\Domain\AccountCollectionInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Ramsey\Uuid\UuidInterface;

interface AccountStoreInterface extends StoreInterface
{
    public function insert(AccountInterface $data): int;

    public function update(AccountInterface $data): true;

    public function findById(int $id): ?AccountInterface;

    public function findByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findByName(string $name): ?AccountInterface;

    public function findByEmail(EmailType $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
