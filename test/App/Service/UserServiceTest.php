<?php declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Table\UserTable;
use App\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCanNotCreateUserWithExistUser(): void
    {
        $table = $this->createMock(UserTable::class);
        $hydrator = new ReflectionHydrator();

        $user = new User();
        $user->setName('fakeName');
        $user->setEmail('fake@example.com');

        $table->expects($this->once())
            ->method('findByName')
            ->with('fakeName')
            ->willReturn(['name' => 'fakeName']);

        $service = new UserService($table, $hydrator);

        $insert = $service->create($user);

        $this->assertSame(false, $insert);
    }

    public function testCanNotCreateUserWithExistEmail(): void
    {
        $table = $this->createMock(UserTable::class);
        $hydrator = new ReflectionHydrator();

        $user = new User();
        $user->setName('fakeName');
        $user->setEmail('fake@example.com');

        $table->expects($this->once())
            ->method('findByName')
            ->with('fakeName')
            ->willReturn(false);

        $table->expects($this->once())
            ->method('findByEmail')
            ->with('fake@example.com')
            ->willReturn(['email' => 'fake@example.com']);

        $service = new UserService($table, $hydrator);

        $insert = $service->create($user);

        $this->assertSame(false, $insert);
    }

    public function testCanCreateUser(): void
    {
        $table = $this->createMock(UserTable::class);
        $hydrator = new ReflectionHydrator();

        $user = new User();
        $user->setName('fakeName');
        $user->setEmail('fake@example.com');

        $table->expects($this->once())
            ->method('findByName')
            ->with('fakeName')
            ->willReturn(false);

        $table->expects($this->once())
            ->method('findByEmail')
            ->willReturn(false);

        $service = new UserService($table, $hydrator);

        $insert = $service->create($user);

        $this->assertSame(true, $insert);
    }

    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(UserTable::class);
        $hydrator = new ReflectionHydrator();

        $table->expects($this->once())
            ->method('findById')
            ->willReturn(false);

        $service = new UserService($table, $hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(UserTable::class);
        $hydrator = new ReflectionHydrator();
        $user = new User();
        $user->setId(1);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(['id' => 1]);

        $service = new UserService($table, $hydrator);

        $user = $service->findById(1);

        $this->assertInstanceOf(User::class, $user);
    }
}
