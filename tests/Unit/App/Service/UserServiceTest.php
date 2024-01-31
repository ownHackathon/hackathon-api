<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\User;
use App\Enum\UserRole;
use App\Exception\DuplicateEntryException;
use App\Service\User\UserService;
use DateTime;
use InvalidArgumentException;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Unit\Mock\Table\MockUserTable;
use Test\Unit\Mock\TestConstants;

class UserServiceTest extends AbstractService
{
    private UserService $userService;

    private array $testUserValues;

    public function setUp(): void
    {
        parent::setUp();

        $table = new MockUserTable();
        $this->userService = new UserService($table, $this->hydrator, $this->uuid);

        $this->testUserValues = [
            TestConstants::USER_ID,
            UuidV7::fromString(TestConstants::USER_UUID),
            UserRole::USER,
            TestConstants::USER_NAME,
            TestConstants::USER_PASSWORD,
            TestConstants::USER_EMAIL,
            new DateTime(),
            new DateTime(),
        ];
    }

    public function testCanNotCreateUserWithExistUser(): void
    {
        $user = new User(...$this->testUserValues);
        $user = $user->with(['name' => TestConstants::USER_NAME]);

        self::expectException(DuplicateEntryException::class);

        $this->userService->create($user);
    }

    public function testCanNotCreateUserWithExistEmail(): void
    {
        $user = new User(...$this->testUserValues);
        $user = $user->with(['email' => TestConstants::USER_EMAIL]);

        self::expectException(DuplicateEntryException::class);

        $this->userService->create($user);
    }

    public function testCanCreateUser(): void
    {
        $user = new User(...$this->testUserValues);
        $user = $user->with([
            'name' => TestConstants::USER_CREATE_NAME,
            'email' => TestConstants::USER_CREATE_EMAIL,
        ]);

        $insert = $this->userService->create($user);

        self::assertSame(1, $insert);
    }

    public function testCanNotCreateUser(): void
    {
        $user = new User();
        $user->setName(TestConstants::USER_NAME);
        $user->setEmail(TestConstants::USER_EMAIL);

        self::expectException(DuplicateEntryException::class);

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
