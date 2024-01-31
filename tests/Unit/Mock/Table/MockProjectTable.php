<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Table\ProjectTable;
use Test\Unit\Mock\Database\MockQuery;

readonly class MockProjectTable extends ProjectTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): array
    {
        return $id === 1 ? ['id' => $id] : [];
    }

    public function findByParticipantId(int $id): bool|array
    {
        return $id === 1 ? ['participantId' => $id] : false;
    }
}
