<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\User;
use App\Exception\DuplicateEntryException;
use App\Service\User\UserService;
use App\System\Hydrator\Strategy\UuidStrategy;
use DateTimeImmutable;
use InvalidArgumentException;
use Test\Data\Entity\UserTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Table\MockUserTable;

class UserServiceTest extends AbstractService
{
    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $table = new MockUserTable();
        $this->hydrator->addStrategy(UuidStrategy::class, $this->uuidStrategy);
        $this->userService = new UserService($table, $this->hydrator, $this->uuid);
    }

    public function testCanNotCreateUserWithExistUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(name: TestConstants::USER_NAME);

        self::expectException(DuplicateEntryException::class);

        $this->userService->create($user);
    }

    public function testCanNotCreateUserWithExistEmail(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(email: TestConstants::USER_EMAIL);

        self::expectException(DuplicateEntryException::class);

        $this->userService->create($user);
    }

    public function testCanCreateUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(
            name: TestConstants::USER_CREATE_NAME,
            email: TestConstants::USER_CREATE_EMAIL,
        );

        $insert = $this->userService->create($user);

        self::assertSame(1, $insert);
    }

    public function testCanNotCreateUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(
            name: TestConstants::USER_NAME,
            email: TestConstants::USER_EMAIL,
        );

        self::expectException(DuplicateEntryException::class);

        $this->userService->create($user);
    }

    public function testCanUpdateLastUserActionTime(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(
            id: TestConstants::USER_ID,
            lastActionAt: new DateTimeImmutable(),
        );

        $update = $this->userService->updateLastUserActionTime($user);

        self::assertInstanceOf(User::class, $update);
    }

    public function testCanNotUpdateUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(id: TestConstants::USER_ID_UNUSED);

        self::expectException(InvalidArgumentException::class);

        $this->userService->update($user);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(id: TestConstants::USER_ID);

        $update = $this->userService->update($user);

        self::assertSame(true, $update);
    }

    public function testFindByIdResultIsNull(): void
    {
        $result = $this->userService->findById(TestConstants::USER_ID_UNUSED);

        self::assertNull($result);
    }

    public function testCanFindById(): void
    {
        $user = $this->userService->findById(TestConstants::USER_ID);

        self::assertInstanceOf(User::class, $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->userService->findByUuid(TestConstants::USER_UUID);

        self::assertInstanceOf(User::class, $user);
    }

    public function testCanNotFindByUuid(): void
    {
        $user = $this->userService->findByUuid(TestConstants::USER_UUID_UNUSED);

        self::assertNull($user);
    }
}
