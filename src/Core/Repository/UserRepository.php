<?php declare(strict_types=1);

namespace Core\Repository;

use App\Entity\User;
use DateTime;

interface UserRepository extends Repository
{
    public function insert(User $user): int;

    public function update(User $user): int;

    public function updateLastUserActionTime(int $id, DateTime $actionTime): self;

    public function findByUuid(string $uuid): array;

    public function findByName(string $name): array;

    public function findByEMail(string $email): array;
}
