<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Table\AbstractTable;
use App\Test\Mock\Database\MockPDO;
use App\Test\Mock\Database\MockQuery;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use Hoa\Iterator\Mock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function get_class;
use function substr;

abstract class AbstractTableTest extends TestCase
{
    private const TABLE_NAME_OFFSET = 8;
    private const TABLE_SUB_LENGTH = -4;

    protected MockQuery $query;
    protected AbstractTable $table;

    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult
        = [
            0 => ['id' => 1],
        ];

    protected function setUp(): void
    {
        $this->query = new MockQuery();

        $this->table = new (
            'App' . substr(get_class($this), self::TABLE_NAME_OFFSET, self::TABLE_SUB_LENGTH)
        )(
            $this->query
        );
    }
}
