<?php declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Table\UserTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

use function password_hash;

class UserService
{
    public function __construct(
        private UserTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function create(User $user): bool
    {
        $isUser = $this->findByName($user->getName());

        if ($isUser instanceof User) {
            return false;
        }

        $email = $user->getEmail();

        if (null !== $email) {
            $isUser = $this->findByEMail($email);
        }

        if ($isUser instanceof User) {
            return false;
        }

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
        $user->setRoleId(USER::USER_DEFAULT_ROLE);

        $this->table->insert($user);

        return true;
    }

    public function findByName(string $name): ?User
    {
        $user = $this->table->findByName($name);

        return $this->generateUserObject($user);
    }

    private function generateUserObject(bool|array $user): ?User
    {
        if (!$user) {
            return null;
        }

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByEMail(string $email): ?User
    {
        $user = $this->table->findByEMail($email);

        return $this->generateUserObject($user);
    }

    public function findById(int $id): User
    {
        $user = $this->table->findById($id);

        if (!$user) {
            throw new InvalidArgumentException('Could not find user', 400);
        }

        return $this->hydrator->hydrate($user, new User());
    }
}
