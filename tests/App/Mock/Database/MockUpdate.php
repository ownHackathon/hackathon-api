<?php declare(strict_types=1);

namespace App\Test\Mock\Database;

use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;

/**
 * ToDo Fix for Test
 */
class MockUpdate extends Update
{
    public function __construct(Query $fluent, string $table)
    {
        parent::__construct($fluent, $table);
    }

    public function execute($getResultAsPdoStatement = false): bool
    {
        return true;
    }
}
