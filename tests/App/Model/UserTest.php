<?php declare(strict_types=1);

namespace AppTest\Model;

use App\Model\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;
    private string $testTime = '1000-01-01 00:00:00';

    protected function setUp(): void
    {
        $this->user = new User();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull(): void
    {
        $email = $this->user->getEmail();
        $lastLogin = $this->user->getLastAction();

        $this->assertNull($email);
        $this->assertNull($lastLogin);
    }

    public function testCanSetAndGetId(): void
    {
        $userId = $this->user->setId(1);
        $id = $userId->getId();

        $this->assertInstanceOf(User::class, $userId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetUuId(): void
    {
        $userId = $this->user->setUuid('asdfasfd-sadf-asfd-as-dfas-dfdwa');
        $uuid = $userId->getUuid();

        $this->assertInstanceOf(User::class, $userId);
        $this->assertIsString($uuid);
        $this->assertSame('asdfasfd-sadf-asfd-as-dfas-dfdwa', $uuid);
    }

    public function testCanSetAndGetRoleId(): void
    {
        $userRoleId = $this->user->setRoleId(1);
        $roleId = $userRoleId->getRoleId();

        $this->assertInstanceOf(User::class, $userRoleId);
        $this->assertIsInt($roleId);
        $this->assertSame(1, $roleId);
    }

    public function testCanSetAndGetName(): void
    {
        $userName = $this->user->setName('test');
        $name = $userName->getName();

        $this->assertInstanceOf(User::class, $userName);
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetPassword(): void
    {
        $userPassword = $this->user->setPassword('test');
        $password = $userPassword->getPassword();

        $this->assertInstanceOf(User::class, $userPassword);
        $this->assertIsString($password);
        $this->assertSame('test', $password);
    }

    public function testCanSetAndGetEmail(): void
    {
        $email = $this->user->getEmail();
        $this->assertNull($email);

        $userEmail = $this->user->setEmail('test@dev.de');
        $email = $userEmail->getEmail();

        $this->assertInstanceOf(User::class, $userEmail);
        $this->assertIsString($email);
        $this->assertSame('test@dev.de', $email);
    }

    public function testCanSetAndGetRegistrationTime(): void
    {
        $userRegistrationTime = $this->user->setRegistrationTime(new DateTime($this->testTime));
        $registrationTime = $userRegistrationTime->getRegistrationTime();

        $this->assertInstanceOf(User::class, $userRegistrationTime);
        $this->assertInstanceOf(DateTime::class, $registrationTime);
        $this->assertSame($this->testTime, $registrationTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetLastLogin(): void
    {
        $userLastLogin = $this->user->setLastAction(new DateTime($this->testTime));
        $lastLogin = $userLastLogin->getLastAction();

        $this->assertInstanceOf(User::class, $userLastLogin);
        $this->assertInstanceOf(DateTime::class, $lastLogin);
        $this->assertSame($this->testTime, $lastLogin->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetActive(): void
    {
        $userActive = $this->user->setActive(true);
        $active = $this->user->isActive();

        $this->assertInstanceOf(User::class, $userActive);
        $this->assertIsBool($active);
        $this->assertSame(true, $active);
    }
}
