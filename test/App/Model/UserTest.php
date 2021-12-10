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

    public function testCanSetAndGetId()
    {
        $userId = $this->user->setId(1);
        $this->assertInstanceOf(User::class, $userId);
        $id = $userId->getId();
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetRoleId()
    {
        $userRoleId = $this->user->setRoleId(1);
        $this->assertInstanceOf(User::class, $userRoleId);
        $roleId = $userRoleId->getRoleId();
        $this->assertIsInt($roleId);
        $this->assertSame(1, $roleId);
    }

    public function testCanSetAndGetName()
    {
        $userName = $this->user->setName('test');
        $this->assertInstanceOf(User::class, $userName);
        $name = $userName->getName();
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetPassword()
    {
        $userPassword = $this->user->setPassword('test');
        $this->assertInstanceOf(User::class, $userPassword);
        $password = $userPassword->getPassword();
        $this->assertIsString($password);
        $this->assertSame('test', $password);
    }

    public function testCanSetAndGetEmail()
    {
        $email = $this->user->getEmail();
        $this->assertNull($email);
        $userEmail = $this->user->setEmail('test@dev.de');
        $this->assertInstanceOf(User::class, $userEmail);
        $email = $userEmail->getEmail();
        $this->assertIsString($email);
        $this->assertSame('test@dev.de', $email);
    }

    public function testCanSetAndGetRegistrationTime()
    {
        $time = '2021-11-02 18:19:31';
        $userRegistrationTime = $this->user->setRegistrationTime($time);
        $this->assertInstanceOf(User::class, $userRegistrationTime);
        $registrationTime = $userRegistrationTime->getRegistrationTime();
        $this->assertInstanceOf(DateTime::class, $registrationTime);
        $this->assertSame($time, '2021-11-02 18:19:31');
        $this->assertSame($time, $registrationTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetLastLogin()
    {
        $time = '2021-11-02 18:19:31';
        $userLastLogin = $this->user->getLastLogin();
        $this->assertNull($userLastLogin);
        $userLastLogin = $this->user->setLastLogin($time);
        $this->assertInstanceOf(User::class, $userLastLogin);
        $lastLogin = $userLastLogin->getLastLogin();
        $this->assertInstanceOf(DateTime::class, $lastLogin);
        $this->assertSame($time, '2021-11-02 18:19:31');
        $this->assertSame($time, $lastLogin->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetActive()
    {
        $active = $this->user->isActive();
        $this->assertIsBool($active);
        $this->assertSame(false, $active);
        $userActive = $this->user->setActive(true);
        $this->assertInstanceOf(User::class, $userActive);
        $active = $this->user->isActive();
        $this->assertSame(true, $active);
    }
}
