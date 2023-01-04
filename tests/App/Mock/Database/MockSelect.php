<?php declare(strict_types=1);

namespace App\Test\Mock\Database;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PDO;

use function array_key_exists;

class MockSelect extends Select
{
    public function __construct(Query $fluent, string $from)
    {
        parent::__construct($fluent, $from);
    }

    public function fetch(?string $column = null, int $cursorOrientation = PDO::FETCH_ORI_NEXT): array
    {
        if (array_key_exists(1, $this->statements['SELECT'])
            && $this->statements['SELECT'][1] === "COUNT(id) AS countTopic")
        {
            return [
                'countTopic' => 1
            ];
        }
        return [
            'id' => 1,
        ];
    }

    public function fetchAll($index = '', $selectOnly = ''): array
    {
        return [
            0 => ['id' => 1]
        ];
    }
}
