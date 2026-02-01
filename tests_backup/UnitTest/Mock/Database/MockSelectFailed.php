<?php declare(strict_types=1);

namespace UnitTest\Mock\Database;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PDO;

use function array_key_exists;

class MockSelectFailed extends Select
{
    public function __construct(Query $fluent, string $from)
    {
        parent::__construct($fluent, $from);
    }

    public function fetch(?string $column = null, int $cursorOrientation = PDO::FETCH_ORI_NEXT): bool|array
    {
        if (array_key_exists('WHERE', $this->statements)) {
            return $this->handle($this->statements['FROM'], $this->statements['WHERE'], $this->parameters['WHERE']);
        }

        return [];
    }

    public function fetchAll($index = '', $selectOnly = ''): false|array
    {
        return false;
    }

    private function handle(string $from, array $where, array $params): false|array
    {
        return false;
    }
}
