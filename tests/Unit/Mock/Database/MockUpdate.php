<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use Test\Unit\Mock\TestConstants;

use function array_key_exists;

/**
 * ToDo Fix for Test
 */
class MockUpdate extends Update
{
    public function __construct(Query $fluent, string $table)
    {
        parent::__construct($fluent, $table);
    }

    public function execute($getResultAsPdoStatement = false): bool|int
    {
        return match ($this->statements['UPDATE']) {
            'User', 'MockUser' => $this->handleUser(),
            default => true
        };
    }

    private function handleUser(): bool|int
    {
        if (array_key_exists('SET', $this->statements)) {
            if ($this->statements['SET'] === []) {
                return 1;
            }

            if ($this->statements['SET']['lastActionAt']) {
                return match ($this->statements['WHERE'][0][1]) {
                    'id = ?' => $this->parameters['WHERE'][0] === TestConstants::USER_ID ? 1 : false,
                    default => 1
                };
            }
        }

        return false;
    }
}
