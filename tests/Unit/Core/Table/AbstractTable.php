<?php declare(strict_types=1);

namespace Test\Unit\Core\Table;

use Core\Table\AbstractTable as ATable;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\Database\MockQuery;
use Test\Unit\Mock\Database\MockQueryForCanNot;

use function get_class;
use function substr;

abstract class AbstractTable extends TestCase
{
    private const TABLE_NAME_OFFSET = 0;
    private const TABLE_SUB_LENGTH = -4;

    protected MockQuery|MockQueryForCanNot $query;
    protected ATable $table;

    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult
        = [
            0 => ['id' => 1],
        ];

    protected function setUp(): void
    {
        $this->query = new MockQuery();

        preg_match('@(Core.*|App.*)@i', get_class($this), $table);

        $this->table = new (
            substr($table[0], self::TABLE_NAME_OFFSET, self::TABLE_SUB_LENGTH)
        )(
            $this->query
        );
    }
}
