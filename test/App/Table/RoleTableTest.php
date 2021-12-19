<?php declare(strict_types=1);

namespace App\Table;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class RoleTableTest extends TestCase
{
    private RoleTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('fetch')->willReturn([]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new RoleTable($query);

        parent::setUp();
    }

    public function testCanFindById()
    {
        $role = $this->table->findById(1);

        $this->assertIsArray($role);
    }
}
