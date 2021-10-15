<?php declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Table\UserTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class UserService
{
    public function __construct(
        private UserTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
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

        if (!$user) {
            return null;
        }

        return $this->hydrator->hydrate($user, new User());
    }
}
