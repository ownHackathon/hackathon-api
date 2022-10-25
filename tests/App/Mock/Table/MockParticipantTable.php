<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Model\Participant;
use App\Table\ParticipantTable;
use App\Test\Mock\Database\MockQuery;

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

    public function findById(int $id): bool|array
    {
        return $id === 1 ? ['id' => $id] : false;
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
