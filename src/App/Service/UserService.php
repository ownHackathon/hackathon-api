<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\Role;
use App\Model\User;
use App\Table\UserTable;
use DateTime;
use Psr\Log\InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

use function password_hash;

class UserService
{
    public function __construct(
        private readonly UserTable $table,
        private readonly ReflectionHydrator $hydrator,
        private readonly UuidInterface $uuid,
    ) {
    }

    public function updateLastUserActionTime(User $user): User
    {
        $user->setLastAction(new DateTime());

        $this->table->updateLastUserActionTime($user->getId(), $user->getLastAction());

        return $user;
    }

    public function create(User $user, int $role = Role::USER): bool
    {
        if (
            $this->isUserExist($user->getName())
            || $this->isEmailExist($user->getEmail())
        ) {
            return false;
        }

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $user->setPassword($hashedPassword);
        $user->setRoleId($role);
        $user->setUuid($this->uuid->getHex()->toString());

        return $this->table->insert($user);
    }

    public function update(User $user): bool
    {
        return $this->table->update($user);
    }

    private function isUserExist(string $userName): bool
    {
        $user = $this->findByName($userName);

        return $user instanceof User;
    }

    private function isEmailExist(?string $email): bool
    {
        $isUser = null;

        if (null !== $email) {
            $isUser = $this->findByEMail($email);
        }

        if ($isUser instanceof User) {
            return true;
        }

        return false;
    }

    public function findById(int $id): User
    {
        $user = $this->table->findById($id);

        if (!$user) {
            throw new InvalidArgumentException('Could not find user', 400);
        }

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByUuid(string $uuid): User|null
    {
        $user = $this->table->findByUuid($uuid);

        return $user ? $this->hydrator->hydrate($user, new User()) : null;
    }

    public function findByName(string $name): ?User
    {
        $user = $this->table->findByName($name);

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByEMail(string $email): ?User
    {
        $user = $this->table->findByEMail($email);

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByToken(string $token): ?User
    {
        $user = $this->table->findByToken($token);

        return $this->hydrator->hydrate($user, new User());
    }
}
