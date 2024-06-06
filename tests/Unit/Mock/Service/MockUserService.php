<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\User;
use App\Hydrator\ReflectionHydrator;
use App\Service\User\UserService;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Test\Data\Entity\UserTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Table\MockUserTable;

readonly class MockUserService extends UserService
{
    public function __construct()
    {
        parent::__construct(new MockUserTable(), new ReflectionHydrator(), Uuid::uuid7());
    }

    public function findByUuid(string $uuid): User|null
    {
        return $uuid === TestConstants::USER_UUID ? new User(...UserTestEntity::getDefaultUserValue()) : null;
    }

    public function findById(int $id): User
    {
        return new User(...UserTestEntity::getDefaultUserValue());
    }

    public function updateLastUserActionTime(User $user): User
    {
        return $user->with(lastActionAt: new DateTimeImmutable(TestConstants::TIME));
    }
}
