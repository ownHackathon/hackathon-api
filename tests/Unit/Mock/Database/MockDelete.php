<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Query;
use Test\Data\TestConstants;

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

    private function handle(string $table, array $where, array $value): bool
    {
        return match ($table) {
            'Event', 'MockEvent' => $this->handleEvent($where, $value),
            default => false,
        };
    }

    private function handleEvent(array $where, array $value): bool
    {
        if ($where[0][1] === 'id = ?' && $value[0] === TestConstants::EVENT_ID) {
            return true;
        }

        return false;
    }
}
