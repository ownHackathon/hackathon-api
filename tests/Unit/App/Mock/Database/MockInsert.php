<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Database;

use Test\Unit\App\Mock\TestConstants;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Query;

class MockInsert extends Insert
{
    public function __construct(Query $fluent, string $table, array $values)
    {
        parent::__construct($fluent, $table, $values);
    }

    public function execute($sequence = null): int|bool
    {
        return $this->handle($this->statements['INSERT INTO'], $this->statements['VALUES']);
    }

    private function handle(string $table, array $values): int|bool
    {
        return match($table) {
            'Event', 'MockEvent' => $this->handleEvent($values),
            'User', 'MockUser' => $this->handleUser($values),
            'Participant', 'MockParticipant' => $this->handleParticipant($values),
            default => false,
        };
    }

    private function handleEvent(array $values): int|bool
    {
        if ($values[0]['title'] === TestConstants::EVENT_CREATE_TITLE) {
            return 1;
        }

        return false;
    }

    private function handleParticipant(array $values): int|bool
    {
        if ($values[0]['userId'] === TestConstants::USER_CREATE_ID) {
            return 1;
        }

        return false;
    }

    private function handleUser(array $values): int|bool
    {

        if ($values[0]['name'] === TestConstants::USER_CREATE_NAME) {
            return 1;
        }

        return false;
    }
}
