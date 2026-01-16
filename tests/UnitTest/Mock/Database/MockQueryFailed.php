<?php declare(strict_types=1);

namespace UnitTest\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;

class MockQueryFailed extends Query
{
    public function __construct()
    {
        parent::__construct(new MockPDO());
    }

    public function insertInto(?string $table = null, array $values = []): Insert
    {
        return new MockInsertFailed($this, $table, $values);
    }

    public function update(?string $table = null, $set = [], ?int $primaryKey = null): Update
    {
        return new MockUpdateFailed($this, $table);
    }

    public function delete(?string $table = null, ?int $primaryKey = null): Delete
    {
        return new MockDeleteFailed($this, $table);
    }

    public function from(?string $table = null, ?int $primaryKey = null): Select
    {
        return new MockSelectFailed($this, $table);
    }
}
