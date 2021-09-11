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

    public function findById(int $userId): User
    {
        $user = $this->table->findByUserId($userId);

        if (!isset($user)) {
            throw new InvalidArgumentException('Could not find user', 400);
        }

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByUserName(string $userName): ?User
    {
        $user = $this->table->findByUserName($userName);

        if (!isset($user)) {
            return null;
        }

        return $this->hydrator->hydrate($user, new User());
    }
}
