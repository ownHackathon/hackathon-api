<?php declare(strict_types=1);

namespace App\Test\Mock\Database;

use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Query;

class MockInsert extends Insert
{
    public function __construct(Query $fluent, string $table, array $values)
    {
        parent::__construct($fluent, $table, $values);
    }

    public function execute($sequence = null): bool
    {
        return true;
    }
}
