<?php declare(strict_types=1);

namespace App\Table;

use App\Model\User;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class UserTableTest extends TestCase
{
    use TableTestMockTrait;

    private Query $query;
    private Select $select;

    protected function setUp(): void
    {
        $this->query = $this->getQueryMock();
        $this->select = $this->getSelectMockWithTable('User');

        parent::setUp();
    }

    public function testCanInsertUser()
    {
        $query = clone $this->query;
        $insert = $this->createMock(Insert::class);

        $user = new User();
        $values = [
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        $query->expects($this->exactly(1))
            ->method('insertInto')
            ->with('User', $values)
            ->willReturn($insert);

        $insert->expects($this->exactly(1))
            ->method('execute')
            ->willReturn('');

        $table = new UserTable($query);

        $insertUser = $table->insert($user);

        $this->assertInstanceOf(UserTable::class, $insertUser);
    }

    public function testCanFindById()
    {
        $select = clone $this->select;
        $query = $this->getQueryMockFromTable('User', $select);

        $select->expects($this->exactly(1))
            ->method('where')
            ->with('id', 1)
            ->willReturnSelf();

        $select->expects($this->exactly(1))
            ->method('fetch')
            ->willReturn([]);

        $table = new UserTable($query);
        $user = $table->findById(1);

        $this->assertIsArray($user);
    }

    public function testCanFindAll()
    {
        $select = clone $this->select;
        $query = $this->getQueryMockFromTable('User', $select);

        $select->expects($this->exactly(1))
            ->method('fetchAll')
            ->willReturn([]);

        $table = new UserTable($query);
        $user = $table->findAll();

        $this->assertIsArray($user);
    }

    public function testCanFindByName()
    {
        $select = clone $this->select;
        $query = $this->getQueryMockFromTable('User', $select);

        $select->expects($this->exactly(1))
            ->method('where')
            ->with('name', 'Testname')
            ->willReturnSelf();

        $select->expects($this->exactly(1))
            ->method('fetch')
            ->willReturn([]);

        $table = new UserTable($query);
        $user = $table->findByName('Testname');

        $this->assertIsArray($user);
    }

    public function testCanFindByEMail()
    {
        $select = clone $this->select;
        $query = $this->getQueryMockFromTable('User', $select);

        $select->expects($this->exactly(1))
            ->method('where')
            ->with('email', 'test@example.com')
            ->willReturnSelf();

        $select->expects($this->exactly(1))
            ->method('fetch')
            ->willReturn([]);

        $table = new UserTable($query);
        $user = $table->findByEMail('test@example.com');

        $this->assertIsArray($user);
    }
}
