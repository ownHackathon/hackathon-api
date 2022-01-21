<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Role;
use App\Model\User;
use App\Table\UserTable;
use DateTime;
use Laminas\Hydrator\ClassMethodsHydrator;
use App\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

use function password_hash;

class UserService
{
    public function __construct(
        private UserTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function create(User $user, int $role = Role::USER): bool
    {
        if (
            $this->isUserExist($user->getName()) ||
            $this->isEmailExist($user->getEmail())
        ) {
            return false;
        }

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $user->setPassword($hashedPassword);
        $user->setRoleId($role);

        $this->table->insert($user);

        return true;
    }

    public function findById(int $id): User
    {
        $user = $this->table->findById($id);

        if (!$user) {
            throw new InvalidArgumentException('Could not find user', 400);
        }

        return $this->hydrator->hydrate($user, new User());
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

    private function isUserExist(string $userName): bool
    {
        $isUser = $this->findByName($userName);

        if ($isUser instanceof User) {
            return true;
        }

        return false;
    }
}
