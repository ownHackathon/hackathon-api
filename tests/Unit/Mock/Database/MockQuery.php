<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;

class MockQuery extends Query
{
    public function __construct()
    {
        parent::__construct(new MockPDO());
    }

    public function insertInto(?string $table = null, array $values = []): Insert
    {
        return new MockInsert($this, $table, $values);
    }

    public function update(?string $table = null, $set = [], ?int $primaryKey = null): Update
    {
        return new MockUpdate($this, $table);
    }

    public function delete(?string $table = null, ?int $primaryKey = null): Delete
    {
        return new MockDelete($this, $table);
    }

    public function from(?string $table = null, ?int $primaryKey = null): Select
    {
        return new MockSelect($this, $table);
    }
}
