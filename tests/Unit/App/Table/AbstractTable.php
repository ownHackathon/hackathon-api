<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Table\AbstractTable as ATable;
use PHPUnit\Framework\TestCase;
use Test\Unit\App\Mock\Database\MockQuery;
use Test\Unit\App\Mock\Database\MockQueryForCanNot;
use Test\Unit\App\Mock\TestConstants;

use function get_class;
use function substr;

abstract class AbstractTable extends TestCase
{
    private const TABLE_NAME_OFFSET = 13;
    private const TABLE_SUB_LENGTH = -4;

    protected MockQuery|MockQueryForCanNot $query;
    protected ATable $table;

    protected array $fetchResult = ['id' => TestConstants::USER_ID];
    protected array $fetchAllResult
        = [
            0 => ['id' => TestConstants::USER_ID],
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
