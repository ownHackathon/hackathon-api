<?php declare(strict_types=1);

namespace App\Test\Mock\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\User;
use App\Service\UserService;
use App\Test\Mock\Table\MockUserTable;
use App\Test\Mock\TestConstants;
use Ramsey\Uuid\Uuid;

class MockUserService extends UserService
{
    public function __construct()
    {
        parent::__construct(new MockUserTable(), new ReflectionHydrator(), Uuid::uuid4());
    }

    public function findByUuid(string $uuid): User|null
    {
        if ($uuid === TestConstants::USER_UUID) {
            return new User();
        }

        return null;
    }
}
