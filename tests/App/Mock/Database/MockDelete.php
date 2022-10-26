<?php declare(strict_types=1);

namespace App\Test\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Query;

class MockDelete extends Delete
{
    public function __construct(Query $fluent, string $table)
    {
        parent::__construct($fluent, $table);
    }

    public function execute(): int|bool
    {
        return 1;
    }
}
