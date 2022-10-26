<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Model\User;
use App\Service\UserService;
use App\Test\Mock\Table\MockUserTable;

class UserServiceTest extends AbstractServiceTest
{
    private UserService $service;

    public function setUp(): void
    {
        parent::setUp();

        $table = new MockUserTable();
        $this->service = new UserService($table, $this->hydrator, $this->uuid);
    }

    public function testCanNotCreateUserWithExistUser(): void
    {
        $user = new User();
        $user->setName('FakeNotCreateUser');

        $insert = $this->service->create($user);

        $this->assertSame(false, $insert);
    }

    public function testCanNotCreateUserWithExistEmail(): void
    {
        $user = new User();
        $user->setEmail('FakeNotCreateEMail');

        $insert = $this->service->create($user);

        $this->assertSame(false, $insert);
    }

    public function testCanCreateUser(): void
    {
        $user = new User();
        $user->setName('fakeName');
        $user->setEmail('fake@example.com');

        $insert = $this->service->create($user);

        $this->assertSame(true, $insert);
    }

    public function testCanUpdateLastUserActionTime(): void
    {
        $user = new User();

        $update = $this->service->updateLastUserActionTime($user);

        $this->assertInstanceOf(User::class, $update);
    }

    public function testCanNotUpdateUser(): void
    {
        $user = new User();
        $user->setId(2);

        $update = $this->service->update($user);

        $this->assertSame(false, $update);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User();
        $user->setId(1);

        $update = $this->service->update($user);

        $this->assertSame(true, $update);
    }

    public function testFindByIdThrowException(): void
    {
        $this->expectException('InvalidArgumentException');

        $this->service->findById(2);
    }

    public function testCanFindById(): void
    {
        $user = $this->service->findById(1);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->service->findByUuid('FakeUserUuid');

        $this->assertInstanceOf(User::class, $user);
    }

    public function testCanNotFindByUuid(): void
    {
        $user = $this->service->findByUuid('FakeUserNotUuid');

        $this->assertNull($user);
    }

    public function testCanNotFindByToken(): void
    {
        $user = $this->service->findByToken('FakeUserNotToken');

        $this->assertNull($user);
    }

    public function testCanFindByToken(): void
    {
        $user = $this->service->findByToken('FakeUserToken');

        $this->assertInstanceOf(User::class, $user);
    }
}
