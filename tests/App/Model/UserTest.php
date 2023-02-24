<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Entity\User;
use App\Test\Mock\TestConstants;
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
        $userId = $this->user->setUuid(TestConstants::USER_UUID);
        $uuid = $userId->getUuid();

        $this->assertInstanceOf(User::class, $userId);
        $this->assertIsString($uuid);
        $this->assertSame(TestConstants::USER_UUID, $uuid);
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
        $userName = $this->user->setName(TestConstants::USER_NAME);
        $name = $userName->getName();

        $this->assertInstanceOf(User::class, $userName);
        $this->assertIsString($name);
        $this->assertSame(TestConstants::USER_NAME, $name);
    }

    public function testCanSetAndGetPassword(): void
    {
        $userPassword = $this->user->setPassword(TestConstants::USER_PASSWORD);
        $password = $userPassword->getPassword();

        $this->assertInstanceOf(User::class, $userPassword);
        $this->assertIsString($password);
        $this->assertSame(TestConstants::USER_PASSWORD, $password);
    }

    public function testCanSetAndGetEmail(): void
    {
        $email = $this->user->getEmail();
        $this->assertNull($email);

        $userEmail = $this->user->setEmail(TestConstants::USER_EMAIL);
        $email = $userEmail->getEmail();

        $this->assertInstanceOf(User::class, $userEmail);
        $this->assertIsString($email);
        $this->assertSame(TestConstants::USER_EMAIL, $email);
    }

    public function testCanSetAndGetRegistrationTime(): void
    {
        $userRegistrationTime = $this->user->setRegistrationTime(new DateTime(TestConstants::TIME));
        $registrationTime = $userRegistrationTime->getRegistrationTime();

        $this->assertInstanceOf(User::class, $userRegistrationTime);
        $this->assertInstanceOf(DateTime::class, $registrationTime);
        $this->assertSame(TestConstants::TIME, $registrationTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetLastLogin(): void
    {
        $userLastLogin = $this->user->setLastAction(new DateTime(TestConstants::TIME));
        $lastLogin = $userLastLogin->getLastAction();

        $this->assertInstanceOf(User::class, $userLastLogin);
        $this->assertInstanceOf(DateTime::class, $lastLogin);
        $this->assertSame(TestConstants::TIME, $lastLogin->format(DateTimeFormat::ISO_8601->value));
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
        $userToken = $this->user->setToken(TestConstants::USER_TOKEN);
        $token = $this->user->getToken();

        $this->assertInstanceOf(User::class, $userToken);
        $this->assertIsString($token);
        $this->assertSame(TestConstants::USER_TOKEN, $token);
    }
}
