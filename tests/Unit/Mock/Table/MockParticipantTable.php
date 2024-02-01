<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Entity\Participant;
use App\Table\ParticipantTable;
use Test\Data\Entity\ParticipantTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Database\MockQuery;

readonly class MockParticipantTable extends ParticipantTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function remove(Participant $participant): bool
    {
        return $participant->id === TestConstants::PARTICIPANT_ID;
    }

    public function findById(int $id): array
    {
        return $id === TestConstants::PARTICIPANT_ID ? ['id' => $id]
            + ParticipantTestEntity::getDefaultParticipantValue() : [];
    }

    public function findByUserId(int $userId): array
    {
        return $userId === TestConstants::USER_ID
            ? ['userId' => $userId] + ParticipantTestEntity::getDefaultParticipantValue()
            : [];
    }

    public function findUserForAnEvent(int $userId, int $eventId): array
    {
        return $userId === TestConstants::USER_ID && $eventId === TestConstants::EVENT_ID
            ? [
                'userId' => $userId,
                'eventId' => $eventId,
            ] + ParticipantTestEntity::getDefaultParticipantValue()
            : [];
    }
}
