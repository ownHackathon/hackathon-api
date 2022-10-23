<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Table\AbstractTable;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function get_class;
use function substr;

abstract class AbstractTableTest extends TestCase
{
    private const TABLE_NAME_OFFSET = 8;
    private const TABLE_SUB_LENGTH = -4;
    protected AbstractTable $table;
    protected Query&MockObject $query;
    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult
        = [
            0 => ['id' => 1],
        ];

    protected function setUp(): void
    {
        $this->query = $this->createMock(Query::class);

        $this->table = new (
            'App' . substr(get_class($this), self::TABLE_NAME_OFFSET, self::TABLE_SUB_LENGTH)
        )(
            $this->query
        );
    }

    protected function createSelect(): Select&MockObject
    {
        $select = $this->createMock(Select::class);

        $this->query->expects($this->once())
            ->method('from')
            ->willReturn($select);

        return $select;
    }

    protected function createInsert(array $values): Insert&MockObject
    {
        $insert = $this->createMock(Insert::class);

        $this->query->expects($this->once())
            ->method('insertInto')
            ->with($this->table->getTableName(), $values)
            ->willReturn($insert);

        return $insert;
    }

    protected function createUpdate(array $values): Update&MockObject
    {
        $update = $this->createMock(Update::class);

        $this->query->expects($this->once())
            ->method('update')
            ->with($this->table->getTableName(), $values)
            ->willReturn($update);

        return $update;
    }

    protected function configureSelectWithOneWhere(string $where, mixed $value): Select&MockObject
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('where')
            ->with($where, $value)
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetch')
            ->willReturn($this->fetchResult);

        return $select;
    }
}
