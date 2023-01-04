<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Table\ProjectTable;
use App\Test\Mock\Database\MockQuery;

class MockProjectTable extends ProjectTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): bool|array
    {
        return $id === 1 ? ['id' => $id] : false;
    }

    public function findByParticipantId(int $id): bool|array
    {
        return $id === 1 ? ['participantId' => $id] : false;
    }
}
