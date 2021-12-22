<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Role;
use App\Table\RoleTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use App\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class RoleService
{
    public function __construct(
        private RoleTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): Role
    {
        $role = $this->table->findById($id);

        if (!$role) {
            throw new InvalidArgumentException('Could not find Role', 400);
        }

        return $this->hydrator->hydrate($role, new Role());
    }

    /** @return Role[] */
    public function findAll(): array
    {
        $roles = $this->table->findAll();

        return $this->hydrator->hydrateList($roles, Role::class);
    }
}
