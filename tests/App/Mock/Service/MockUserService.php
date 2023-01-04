<?php declare(strict_types=1);

namespace App\Test\Mock\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\User;
use App\Service\UserService;
use App\Test\Mock\Table\MockUserTable;
use App\Test\Mock\TestConstants;
use DateTime;
use Ramsey\Uuid\Uuid;

class MockUserService extends UserService
{
    public function __construct()
    {
        parent::__construct(new MockUserTable(), new ReflectionHydrator(), Uuid::uuid4());
    }

    public function findByUuid(string $uuid): User|null
    {
        return ($uuid === TestConstants::USER_UUID) ? new User() : null;
    }

    public function updateLastUserActionTime(User $user): User
    {
        return $user->setLastAction(new DateTime(TestConstants::TIME));
    }
}
