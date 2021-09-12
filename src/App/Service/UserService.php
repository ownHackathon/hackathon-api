<?php declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Table\UserTable;
use Laminas\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class UserService
{
    public function __construct(
        protected UserTable $table,
        protected ReflectionHydrator $hydrator
    ) {
    }

    public function findById(int $id): User
    {
        $user = $this->table->findById($id);

        if (!isset($user)) {
            throw new InvalidArgumentException('Could not find user', 400);
        }

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByName(string $name): ?User
    {
        $user = $this->table->findByName($name);

        if (!isset($user)) {
            return null;
        }

        return $this->hydrator->hydrate($user, new User());
    }
}
