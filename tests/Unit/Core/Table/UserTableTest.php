<?php declare(strict_types=1);

namespace Test\Unit\Core\Table;

use App\Entity\User;
use Core\Exception\DuplicateEntryException;
use Core\Table\UserTable;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use Test\Data\Entity\UserTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Database\MockQueryForCanNot;

/**
 * @property UserTable $table
 */
class UserTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        self::assertSame('User', $this->table->getTableName());
    }

    public function testCanInsertUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(name: TestConstants::USER_CREATE_NAME);

        $affectedRowCount = $this->table->insert($user);

        self::assertSame(1, $affectedRowCount);
    }

    public function testCanNotInsertUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(name: TestConstants::USER_NAME);

        self::expectException(DuplicateEntryException::class);

        $this->table->insert($user);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(
            id: TestConstants::USER_ID,
            registrationAt: new DateTimeImmutable(),
            lastActionAt: new DateTimeImmutable(),
        );

        $updateUser = $this->table->update($user);

        self::assertSame(1, $updateUser);
    }

    public function testUpdateUserThrowException(): void
    {
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(
            id: TestConstants::USER_ID_THROW_EXCEPTION,
            lastActionAt: new DateTimeImmutable(),
        );

        $table = new UserTable(new MockQueryForCanNot());

        self::expectException(InvalidArgumentException::class);

        $table->update($user);
    }

    public function testCanUpdateLastUserActionTime(): void
    {
        $updateUser = $this->table->updateLastUserActionTime(TestConstants::USER_ID, new DateTime());

        self::assertInstanceOf(UserTable::class, $updateUser);
    }

    public function testUpdateLastUserActionTimeThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->table->updateLastUserActionTime(TestConstants::USER_ID_THROW_EXCEPTION, new DateTime());
    }

    public function testCanFindById(): void
    {
        $user = $this->table->findById(TestConstants::USER_ID);

        self::assertEquals(UserTestEntity::getDefaultUserValue(), $user);
    }

    public function testFindByIdHasEmptyResult(): void
    {
        $user = $this->table->findById(TestConstants::USER_ID_UNUSED);

        self::assertSame([], $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->table->findByUuid(TestConstants::USER_UUID);

        self::assertEquals(UserTestEntity::getDefaultUserValue(), $user);
    }

    public function testFindByUuidHasEmptyResult(): void
    {
        $user = $this->table->findByUuid(TestConstants::USER_UUID_UNUSED);

        self::assertSame([], $user);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        self::assertEquals([0 => UserTestEntity::getDefaultUserValue()], $users);
    }

    public function testFindAllReturnedEmpty(): void
    {
        $table = new UserTable(new MockQueryForCanNot());
        $users = $table->findAll();

        self::assertSame([], $users);
    }

    public function testCanFindByName(): void
    {
        $user = $this->table->findByName(TestConstants::USER_NAME);

        self::assertEquals(UserTestEntity::getDefaultUserValue(), $user);
    }

    public function testFindByNameHasEmptyResult(): void
    {
        $user = $this->table->findByName(TestConstants::USER_NAME_UNUSED);

        self::assertSame([], $user);
    }

    public function testCanFindByEmail(): void
    {
        $user = $this->table->findByEMail(TestConstants::USER_EMAIL);

        self::assertEquals(UserTestEntity::getDefaultUserValue(), $user);
    }

    public function testFindByEmailHasEmptyResult(): void
    {
        $user = $this->table->findByEMail(TestConstants::USER_EMAIL_UNUSED);

        self::assertSame([], $user);
    }
}
