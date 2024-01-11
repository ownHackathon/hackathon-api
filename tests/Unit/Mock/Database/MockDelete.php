<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Query;
use Test\Unit\Mock\TestConstants;

class MockDelete extends Delete
{
    public function __construct(Query $fluent, string $table)
    {
        parent::__construct($fluent, $table);
    }

    public function execute(): bool|int
    {
        return $this->handle($this->statements['DELETE FROM'], $this->statements['WHERE'], $this->parameters['WHERE']);
    }

    private function handle(string $table, array $where, array $value): bool|int
    {
        return match ($table) {
            'Event', 'MockEvent' => $this->handleEvent($where, $value),
            default => false,
        };
    }

    private function handleEvent(array $where, array $value): bool|int
    {
        if ($where[0][1] === 'id = ?' && $value[0] === TestConstants::EVENT_ID) {
            return 1;
        }

        return false;
    }
}
