<?php declare(strict_types=1);

namespace App\Table;

use App\Model\User;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class UserTableTest extends TestCase
{
    private UserTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('fetch')->willReturn([]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new UserTable($query);

        parent::setUp();
    }

    public function testCanInsert()
    {
        $user = new User();
        $insertUser = $this->table->insert($user);

        $this->assertInstanceOf(UserTable::class, $insertUser);
    }

    public function testCanFindById()
    {
        $user = $this->table->findById(1);

        $this->assertIsArray($user);
    }

    public function testCanFindAll()
    {
        $user = $this->table->findAll();

        $this->assertIsArray($user);
    }

    public function testFindByName()
    {
        $user = $this->table->findByName('Testname');

        $this->assertIsArray($user);
    }

    public function testCanFindByEmail()
    {
        $user = $this->table->findByEMail('test@excample.com');

        $this->assertIsArray($user);
    }
}
