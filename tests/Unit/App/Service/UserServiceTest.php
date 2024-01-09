<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\User;
use App\Service\UserService;
use InvalidArgumentException;
use Test\Unit\App\Mock\Table\MockUserTable;
use Test\Unit\App\Mock\TestConstants;

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

        $this->assertSame(1, $insert);
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

        $update = $this->userService->updateLastUserActionTime($user);

        $this->assertInstanceOf(User::class, $update);
    }

    public function testCanNotUpdateUser(): void
    {
        $user = new User();
        $user->setId(2);

        self::expectException(InvalidArgumentException::class);

        $this->userService->update($user);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User();
        $user->setId(1);

        $update = $this->userService->update($user);

        $this->assertSame(true, $update);
    }

    public function testFindByIdThrowException(): void
    {
        $this->expectException('InvalidArgumentException');

        $this->userService->findById(2);
    }

    public function testCanFindById(): void
    {
        $user = $this->userService->findById(TestConstants::USER_ID);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->userService->findByUuid(TestConstants::USER_UUID);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testCanNotFindByUuid(): void
    {
        $user = $this->userService->findByUuid('FakeUserNotUuid');

        $this->assertNull($user);
    }

    public function testCanNotFindByToken(): void
    {
        $user = $this->userService->findByToken('FakeUserNotToken');

        $this->assertNull($user);
    }

    public function testCanFindByToken(): void
    {
        $user = $this->userService->findByToken(TestConstants::USER_TOKEN);

        $this->assertInstanceOf(User::class, $user);
    }
}
