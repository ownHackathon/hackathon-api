<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Database;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Query;

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
            'Account', 'AccountAccessAuth' => true,
            default => false,
        };
    }
}
