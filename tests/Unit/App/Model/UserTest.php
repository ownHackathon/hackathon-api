<?php declare(strict_types=1);

namespace Test\Unit\App\Model;

use App\Entity\User;
use App\Enum\DateTimeFormat;
use DateTime;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull(): void
    {
        $lastLogin = $this->user->getLastAction();

        self::assertNull($lastLogin);
    }

    public function testCanSetAndGetId(): void
    {
        $userId = $this->user->setId(1);
        $id = $userId->getId();

        self::assertInstanceOf(User::class, $userId);
        self::assertIsInt($id);
        self::assertSame(1, $id);
    }

    public function testCanSetAndGetUuId(): void
    {
        $userId = $this->user->setUuid(TestConstants::USER_UUID);
        $uuid = $userId->getUuid();

        self::assertInstanceOf(User::class, $userId);
        self::assertIsString($uuid);
        self::assertSame(TestConstants::USER_UUID, $uuid);
    }

    public function testCanSetAndGetRoleId(): void
    {
        $userRoleId = $this->user->setRoleId(1);
        $roleId = $userRoleId->getRoleId();

        self::assertInstanceOf(User::class, $userRoleId);
        self::assertIsInt($roleId);
        self::assertSame(1, $roleId);
    }

    public function testCanSetAndGetName(): void
    {
        $userName = $this->user->setName(TestConstants::USER_NAME);
        $name = $userName->getName();

        self::assertInstanceOf(User::class, $userName);
        self::assertIsString($name);
        self::assertSame(TestConstants::USER_NAME, $name);
    }

    public function testCanSetAndGetPassword(): void
    {
        $userPassword = $this->user->setPassword(TestConstants::USER_PASSWORD);
        $password = $userPassword->getPassword();

        self::assertInstanceOf(User::class, $userPassword);
        self::assertIsString($password);
        self::assertSame(TestConstants::USER_PASSWORD, $password);
    }

    public function testCanSetAndGetEmail(): void
    {
        $email = $this->user->getEmail();
        $this->assertSame('', $email);

        $userEmail = $this->user->setEmail(TestConstants::USER_EMAIL);
        $email = $userEmail->getEmail();

        self::assertInstanceOf(User::class, $userEmail);
        self::assertIsString($email);
        self::assertSame(TestConstants::USER_EMAIL, $email);
    }

    public function testCanSetAndGetRegistrationTime(): void
    {
        $userRegistrationTime = $this->user->setRegistrationTime(new DateTime(TestConstants::TIME));
        $registrationTime = $userRegistrationTime->getRegistrationTime();

        self::assertInstanceOf(User::class, $userRegistrationTime);
        self::assertInstanceOf(DateTime::class, $registrationTime);
        self::assertSame(TestConstants::TIME, $registrationTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetLastLogin(): void
    {
        $userLastLogin = $this->user->setLastAction(new DateTime(TestConstants::TIME));
        $lastLogin = $userLastLogin->getLastAction();

        self::assertInstanceOf(User::class, $userLastLogin);
        self::assertInstanceOf(DateTime::class, $lastLogin);
        self::assertSame(TestConstants::TIME, $lastLogin->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetActive(): void
    {
        $userActive = $this->user->setActive(true);
        $active = $this->user->isActive();

        self::assertInstanceOf(User::class, $userActive);
        self::assertIsBool($active);
        self::assertSame(true, $active);
    }

    public function testCanSetAndGetToken(): void
    {
        $userToken = $this->user->setToken(TestConstants::USER_TOKEN);
        $token = $this->user->getToken();

        self::assertInstanceOf(User::class, $userToken);
        self::assertIsString($token);
        self::assertSame(TestConstants::USER_TOKEN, $token);
    }
}
