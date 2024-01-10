<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\User;
use App\Table\UserTable;
use DateTime;
use InvalidArgumentException;
use Test\Unit\App\Mock\Database\MockQueryForFetchAll;
use Test\Unit\App\Mock\TestConstants;

/**
 * @property UserTable $table
 */
class UserTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        $this->assertSame('User', $this->table->getTableName());
    }

    public function testCanInsertUser(): void
    {
        $user = new User();
        $user->setName(TestConstants::USER_CREATE_NAME);

        $affectedRowCount = $this->table->insert($user);

        self::assertSame(1, $affectedRowCount);
    }

    public function testCanNotInsertUser(): void
    {
        $user = new User();
        $user->setName(TestConstants::USER_NAME);

        self::expectException(InvalidArgumentException::class);

        $this->table->insert($user);
    }

    public function testCanUpdateUser(): void
    {
        $user = new User();
        $user->setLastAction(new DateTime());

        $updateUser = $this->table->update($user);

        $this->assertSame(true, $updateUser);
    }

    public function testCanUpdateLastUserActionTime(): void
    {
        $updateUser = $this->table->updateLastUserActionTime(TestConstants::USER_ID, new DateTime());

        $this->assertInstanceOf(UserTable::class, $updateUser);
    }

    public function testCanFindById(): void
    {
        $user = $this->table->findById(TestConstants::USER_ID);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $user = $this->table->findById(TestConstants::USER_ID_UNUSED);

        $this->assertSame([], $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->table->findByUuid(TestConstants::USER_UUID);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testFindAllReturnedEmpty(): void
    {

        $table = new UserTable(new MockQueryForFetchAll());
        $users = $table->findAll();

        $this->assertSame([], $users);
    }

    public function testCanFindByName(): void
    {
        $user = $this->table->findByName(TestConstants::USER_NAME);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByEmail(): void
    {
        $user = $this->table->findByEMail(TestConstants::USER_EMAIL);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByToken(): void
    {
        $user = $this->table->findByToken(TestConstants::USER_TOKEN);

        $this->assertSame($this->fetchResult, $user);
    }
}
