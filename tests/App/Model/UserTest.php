<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Model\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private const TEST_TIME = '1000-01-01 00:00';
    private const TEST_UUID = 'asdfasfdsadfasfdasdfasdfdwa';
    private const TEST_NAME = 'Test Name';
    private const TEST_PASSWORD = 'Test Password';
    private const TEST_EMAIL = 'example@example.com';
    private const TEST_TOKEN = '4e10cfecf3bb51811689956e647705a0';

    private User $user;

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
        $userId = $this->user->setUuid(self::TEST_UUID);
        $uuid = $userId->getUuid();

        $this->assertInstanceOf(User::class, $userId);
        $this->assertIsString($uuid);
        $this->assertSame(self::TEST_UUID, $uuid);
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
        $userName = $this->user->setName(self::TEST_NAME);
        $name = $userName->getName();

        $this->assertInstanceOf(User::class, $userName);
        $this->assertIsString($name);
        $this->assertSame(self::TEST_NAME, $name);
    }

    public function testCanSetAndGetPassword(): void
    {
        $userPassword = $this->user->setPassword(self::TEST_PASSWORD);
        $password = $userPassword->getPassword();

        $this->assertInstanceOf(User::class, $userPassword);
        $this->assertIsString($password);
        $this->assertSame(self::TEST_PASSWORD, $password);
    }

    public function testCanSetAndGetEmail(): void
    {
        $email = $this->user->getEmail();
        $this->assertNull($email);

        $userEmail = $this->user->setEmail(self::TEST_EMAIL);
        $email = $userEmail->getEmail();

        $this->assertInstanceOf(User::class, $userEmail);
        $this->assertIsString($email);
        $this->assertSame(self::TEST_EMAIL, $email);
    }

    public function testCanSetAndGetRegistrationTime(): void
    {
        $userRegistrationTime = $this->user->setRegistrationTime(new DateTime(self::TEST_TIME));
        $registrationTime = $userRegistrationTime->getRegistrationTime();

        $this->assertInstanceOf(User::class, $userRegistrationTime);
        $this->assertInstanceOf(DateTime::class, $registrationTime);
        $this->assertSame(self::TEST_TIME, $registrationTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetLastLogin(): void
    {
        $userLastLogin = $this->user->setLastAction(new DateTime(self::TEST_TIME));
        $lastLogin = $userLastLogin->getLastAction();

        $this->assertInstanceOf(User::class, $userLastLogin);
        $this->assertInstanceOf(DateTime::class, $lastLogin);
        $this->assertSame(self::TEST_TIME, $lastLogin->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetActive(): void
    {
        $userActive = $this->user->setActive(true);
        $active = $this->user->isActive();

        $this->assertInstanceOf(User::class, $userActive);
        $this->assertIsBool($active);
        $this->assertSame(true, $active);
    }

    public function testCanSetAndGetToken(): void
    {
        $userToken = $this->user->setToken(self::TEST_TOKEN);
        $token = $this->user->getToken();

        $this->assertInstanceOf(User::class, $userToken);
        $this->assertIsString($token);
        $this->assertSame(self::TEST_TOKEN, $token);
    }
}
