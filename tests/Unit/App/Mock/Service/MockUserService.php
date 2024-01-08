<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Service;

use App\Hydrator\ReflectionHydrator;
use App\Entity\User;
use App\Service\UserService;
use Test\Unit\App\Mock\Table\MockUserTable;
use Test\Unit\App\Mock\TestConstants;
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
        return $uuid === TestConstants::USER_UUID ? new User() : null;
    }

    public function findById(int $id): User
    {
        return new User();
    }

    public function updateLastUserActionTime(User $user): User
    {
        return $user->setLastAction(new DateTime(TestConstants::TIME));
    }
}