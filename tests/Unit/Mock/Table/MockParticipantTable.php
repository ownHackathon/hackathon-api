<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Entity\Participant;
use App\Table\ParticipantTable;
use Test\Unit\Mock\Database\MockQuery;

class MockParticipantTable extends ParticipantTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function remove(Participant $participant): int|bool
    {
        return $participant->getId() === 1 ? 1 : false;
    }

    public function findById(int $id): array
    {
        return $id === 1 ? ['id' => $id] : [];
    }

    public function findByUserId(int $userId): bool|array
    {
        return $userId === 1 ? ['userId' => 1] : false;
    }

    public function findByUserIdAndEventId(int $userId, int $eventId): bool|array
    {
        if ($userId === 1 && $eventId === 1) {
            return [
                'userId' => $userId,
                'eventId' => $eventId,
            ];
        }

        return false;
    }
}
