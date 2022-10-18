<?php declare(strict_types=1);

namespace AppTest\Table;

use App\Model\User;
use App\Table\UserTable;

/**
 * @property UserTable $table
 */
class UserTableTest extends AbstractTableTest
{
    private const TEST_USER_ID = 1;

    public function testCanInsertUser(): void
    {
        $user = new User();
        $values = [
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'uuid' => $user->getUuid(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn(1);

        $insertUser = $this->table->insert($user);

        $this->assertSame(true, $insertUser);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', self::TEST_USER_ID);

        $user = $this->table->findById(self::TEST_USER_ID);

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByName(): void
    {
        $this->configureSelectWithOneWhere('name', 'fakeName');

        $user = $this->table->findByName('fakeName');

        $this->assertSame($this->fetchResult, $user);
    }

    public function testCanFindByEmail(): void
    {
        $this->configureSelectWithOneWhere('email', 'test@example.com');

        $user = $this->table->findByEMail('test@example.com');

        $this->assertSame($this->fetchResult, $user);
    }
}
