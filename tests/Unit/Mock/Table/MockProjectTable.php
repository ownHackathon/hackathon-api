<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Table\ProjectTable;
use Test\Data\Entity\ProjectTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Database\MockQuery;

readonly class MockProjectTable extends ProjectTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): array
    {
        return $id === TestConstants::PROJECT_ID ? ['id' => $id] + ProjectTestEntity::getDefaultProjectValue() : [];
    }

    public function findByParticipantId(int $id): array
    {
        return $id === TestConstants::PARTICIPANT_ID
            ? ['participantId' => $id] + ProjectTestEntity::getDefaultProjectValue()
            : [];
    }
}
