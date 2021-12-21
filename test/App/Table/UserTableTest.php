<?php declare(strict_types=1);

namespace App\Table;

use App\Model\User;

/**
 * @property UserTable $table
 */
class UserTableTest extends AbstractTableTest
{
    public function testCanInsertUser(): void
    {
        $user = new User();
        $values = [
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn('');

        $insertUser = $this->table->insert($user);

        $this->assertInstanceOf(UserTable::class, $insertUser);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $user = $this->table->findById(1);

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
