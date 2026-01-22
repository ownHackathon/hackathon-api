<?php declare(strict_types=1);

namespace Core\Repository\Account;

use Core\Entity\Account\AccountCollectionInterface;
use Core\Entity\Account\AccountInterface;
use Core\Repository\RepositoryInterface;
use Core\Type\Email;
use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountInterface $data): int;

    public function update(AccountInterface $data): true;

    public function findById(int $id): ?AccountInterface;

    public function findByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findByName(string $name): ?AccountInterface;

    public function findByEmail(Email $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
