<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Table;

use App\Table\ProjectTable;
use Test\Unit\App\Mock\Database\MockQuery;

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
