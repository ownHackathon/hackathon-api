<?php declare(strict_types=1);

namespace Tests\Unit\Core\Persistence;

use Envms\FluentPDO\Query;
use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use InvalidArgumentException;
use Core\Persistence\Store\AbstractTable;
use Core\Persistence\Factory\DatabaseFactory;
use PDOException;

use function expect;
use function test;

final class CoreTestTable extends AbstractTable
{
    public function persist(array $data): int
    {
        return $this->executePersist($data);
    }

    public function persistForTest(array $data): int
    {
        return $this->executePersist($data);
    }
}

test('abstract table exposes table name and fetches all rows', function (): void {
    $query = $this->createMock(Query::class);
    $select = $this->createMock(Select::class);
    $query->expects($this->once())->method('from')->with('CoreTest')->willReturn($select);
    $select->expects($this->once())->method('fetchAll')->willReturn([['id' => 1]]);
    $table = new CoreTestTable($query);

    expect($table->getTableName())->toBe('CoreTest')->and($table->fetchAll())->toBe([['id' => 1]]);
});

test('abstract table reports failed updates and removals', function (): void {
    $query = $this->createMock(Query::class);
    $update = $this->createMock(Update::class);
    $delete = $this->createMock(Delete::class);
    $query->method('update')->willReturn($update);
    $query->method('delete')->willReturn($delete);
    $update->method('execute')->willReturn(false);
    $delete->method('where')->willReturnSelf();
    $delete->method('execute')->willReturn(false);
    $table = new CoreTestTable($query);

    expect(fn (): mixed => $table->update(1, ['id' => 1]))->toThrow(InvalidArgumentException::class)
        ->and(fn (): mixed => $table->remove(['id' => 1]))->toThrow(InvalidArgumentException::class);
});

test('abstract table persists rows through FluentPDO', function (): void {
    $query = $this->createMock(Query::class);
    $insert = $this->createMock(Insert::class);
    $query->expects($this->once())->method('insertInto')->with('CoreTest', ['name' => 'value'])->willReturn($insert);
    $insert->expects($this->once())->method('execute')->willReturn(4);

    expect((new CoreTestTable($query))->persistForTest(['id' => 1, 'name' => 'value']))->toBe(4);
});

test('abstract table rethrows non-duplicate database errors', function (): void {
    $query = $this->createMock(Query::class);
    $insert = $this->createMock(Insert::class);
    $error = new PDOException('database failure');
    $error->errorInfo = ['HY000', 9999, 'database failure'];
    $query->method('insertInto')->willReturn($insert);
    $insert->method('execute')->willThrowException($error);

    expect(fn (): mixed => (new CoreTestTable($query))->persistForTest(['name' => 'value']))->toThrow(PDOException::class);
});

test('database factory supports sqlite configuration', function (): void {
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'database' => [
            'driver' => 'sqlite', 'host' => ':memory:', 'user' => '', 'password' => '',
            'error' => \PDO::ERRMODE_EXCEPTION, 'emulate_prepares' => false,
        ],
    ]);

    expect((new DatabaseFactory())($container))->toBeInstanceOf(\PDO::class);
});
