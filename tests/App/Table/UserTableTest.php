<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Model\User;
use App\Table\UserTable;
use DateTime;

/**
 * @property UserTable $table
 */
class UserTableTest extends AbstractTableTest
{
    private const TEST_USER_ID = 1;
    private const TEST_UUID = 'asdfasfdsadfasfdasdfasdfdwa';
    private const TEST_TOKEN = '4e10cfecf3bb51811689956e647705a0';

    public function testCanGetTableName(): void
    {
        $this->assertSame('User', $this->table->getTableName());
    }

    public function testCanInsertUser(): void
    {
        $user = new User();

        $insertUser = $this->table->insert($user);

        $this->assertSame(true, $insertUser);
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
        $updateUser = $this->table->updateLastUserActionTime(self::TEST_USER_ID, new DateTime());

        $this->assertInstanceOf(UserTable::class, $updateUser);
    }

    public function testCanFindById(): void
    {
        $user = $this->table->findById(self::TEST_USER_ID);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByUuid(): void
    {
        $user = $this->table->findByUuid(self::TEST_UUID);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByName(): void
    {
        $user = $this->table->findByName('fakeName');

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByEmail(): void
    {
        $user = $this->table->findByEMail('test@example.com');

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByToken(): void
    {
        $user = $this->table->findByToken(self::TEST_TOKEN);

        $this->assertSame($this->fetchResult, $user);
    }
}
