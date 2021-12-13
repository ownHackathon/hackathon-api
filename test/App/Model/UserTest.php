<?php declare(strict_types=1);

namespace App\Model;

use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull()
    {
        $email = $this->user->getEmail();
        $lastLogin = $this->user->getLastLogin();

        $this->assertNull($email);
        $this->assertNull($lastLogin);
    }

    public function testCanSetAndGetId()
    {
        $userId = $this->user->setId(1);
        $id = $userId->getId();

        $this->assertInstanceOf(User::class, $userId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetRoleId()
    {
        $userRoleId = $this->user->setRoleId(1);
        $roleId = $userRoleId->getRoleId();

        $this->assertInstanceOf(User::class, $userRoleId);
        $this->assertIsInt($roleId);
        $this->assertSame(1, $roleId);
    }

    public function testCanSetAndGetName()
    {
        $userName = $this->user->setName('test');
        $name = $userName->getName();

        $this->assertInstanceOf(User::class, $userName);
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetPassword()
    {
        $userPassword = $this->user->setPassword('test');
        $password = $userPassword->getPassword();

        $this->assertInstanceOf(User::class, $userPassword);
        $this->assertIsString($password);
        $this->assertSame('test', $password);
    }

    public function testCanSetAndGetEmail()
    {
        $email = $this->user->getEmail();
        $this->assertNull($email);

        $userEmail = $this->user->setEmail('test@dev.de');
        $email = $userEmail->getEmail();

        $this->assertInstanceOf(User::class, $userEmail);
        $this->assertIsString($email);
        $this->assertSame('test@dev.de', $email);
    }

    public function testCanSetAndGetRegistrationTime()
    {
        $time = '1000-01-01 00:00:00';
        $userRegistrationTime = $this->user->setRegistrationTime(new DateTime($time));
        $registrationTime = $userRegistrationTime->getRegistrationTime();

        $this->assertInstanceOf(User::class, $userRegistrationTime);
        $this->assertInstanceOf(DateTime::class, $registrationTime);
        $this->assertSame($time, $registrationTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetLastLogin()
    {
        $time = '1000-01-01 00:00:00';
        $userLastLogin = $this->user->setLastLogin(new DateTime($time));
        $lastLogin = $userLastLogin->getLastLogin();

        $this->assertInstanceOf(User::class, $userLastLogin);
        $this->assertInstanceOf(DateTime::class, $lastLogin);
        $this->assertSame($time, $lastLogin->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetActive()
    {
        $userActive = $this->user->setActive(true);
        $active = $this->user->isActive();

        $this->assertInstanceOf(User::class, $userActive);
        $this->assertIsBool($active);
        $this->assertSame(true, $active);
    }
}
