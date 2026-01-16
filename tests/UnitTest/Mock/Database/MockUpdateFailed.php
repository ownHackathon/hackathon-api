<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Database;

use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;

class MockUpdateFailed extends Update
{
    public function __construct(Query $fluent, string $table)
    {
        parent::__construct($fluent, $table);
    }

    public function execute($getResultAsPdoStatement = false): bool
    {
        return false;
    }
}
