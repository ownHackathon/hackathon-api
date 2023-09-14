<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Table\AbstractTable as ATable;
use App\Test\Mock\Database\MockQuery;
use PHPUnit\Framework\TestCase;

use function get_class;
use function substr;

abstract class AbstractTable extends TestCase
{
    private const TABLE_NAME_OFFSET = 8;
    private const TABLE_SUB_LENGTH = -4;

    protected MockQuery $query;
    protected ATable $table;

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