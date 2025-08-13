<?php declare(strict_types=1);

namespace ownHackathon\Core\Repository;

use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Entity\Account\AccountCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Type\Email;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function insert(AccountInterface $data): true;

    public function update(AccountInterface $data): true;

    public function findById(int $id): ?AccountInterface;

    public function findByUuid(UuidInterface $uuid): ?AccountInterface;

    public function findByName(string $name): ?AccountInterface;

    public function findByEmail(Email $email): ?AccountInterface;

    public function findAll(): AccountCollectionInterface;
}
