<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\User;
use App\Service\UserService;
use DateTime;
use InvalidArgumentException;
use Test\Unit\Mock\Table\MockUserTable;
use Test\Unit\Mock\TestConstants;

class UserServiceTest extends AbstractService
{
    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $table = new MockUserTable();
        $this->userService = new UserService($table, $this->hydrator, $this->uuid);
    }

    public function testCanNotCreateUserWithExistUser(): void
    {
        $user = new User();
        $user->setName('FakeNotCreateUser');

        self::expectException(InvalidArgumentException::class);

        $this->userService->create($user);
    }

    public function testCanNotCreateUserWithExistEmail(): void
    {
        $user = new User();
        $user->setEmail('FakeNotCreateEMail');

        self::expectException(InvalidArgumentException::class);

        $this->userService->create($user);
    }

    public function testCanCreateUser(): void
    {
        $user = new User();
        $user->setName(TestConstants::USER_CREATE_NAME);
        $user->setEmail(TestConstants::USER_CREATE_EMAIL);

        $insert = $this->userService->create($user);

        self::assertSame(1, $insert);
    }

    public function testCanNotCreateUser(): void
    {
        $user = new User();
        $user->setName(TestConstants::USER_NAME);
        $user->setEmail(TestConstants::USER_EMAIL);

        self::expectException(InvalidArgumentException::class);

        $this->userService->create($user);
    }

    public function testCanUpdateLastUserActionTime(): void
    {
        $user = new User();
        $user->setId(TestConstants::USER_ID);
        $user->setLastAction(new DateTime());

        $update = $this->userService->updateLastUserActionTime($user);

        self::assertInstanceOf(User::class, $update);
    }

    public function testCanNotUpdateUser(): void
    {
        $user = new User();
        $user->setId(TestConstants::USER_ID_UNUSED);

        self::expectException(InvalidArgumentException::class);

        $this->userService->update($user);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User();
        $user->setId(TestConstants::USER_ID);

        $update = $this->userService->update($user);

        self::assertSame(true, $update);
    }

    public function testFindByIdThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->userService->findById(TestConstants::USER_ID_THROW_EXCEPTION);
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

    public function testCanNotFindByToken(): void
    {
        $user = $this->userService->findByToken(TestConstants::USER_TOKEN_UNUSED);

        self::assertNull($user);
    }

    public function testCanFindByToken(): void
    {
        $user = $this->userService->findByToken(TestConstants::USER_TOKEN);

        self::assertInstanceOf(User::class, $user);
    }
}
