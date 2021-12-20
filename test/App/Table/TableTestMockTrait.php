<?php declare(strict_types=1);

namespace App\Table;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\MockObject\MockObject;

trait TableTestMockTrait
{
    /**
     * @return MockObject
     */
    public function getQueryMock(): Query
    {
        return $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return MockObject
     */
    public function getSelectMockWithTable(string $table): Select
    {
        return $this->getMockBuilder(Select::class)
            ->setConstructorArgs([$this->query, $table])
            ->getMock();
    }

    /**
     * @return MockObject
     */
    public function getQueryMockFromTable(string $table, Select $select): Query
    {
        $query = clone $this->query;

        $query->expects($this->exactly(1))
            ->method('from')
            ->with($table)
            ->willReturn($select);

        return $query;
    }
}
