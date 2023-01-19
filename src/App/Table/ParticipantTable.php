<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Participant;

class ParticipantTable extends AbstractTable
{
    public function insert(Participant $participant): int|bool
    {
        $values = [
            'userId' => $participant->getUserId(),
            'eventId' => $participant->getEventId(),
            'approved' => $participant->isApproved(),
        ];

        return (int)$this->query->insertInto($this->table, $values)
            ->onDuplicateKeyUpdate(['subscribed' => 1])
            ->execute();
    }

    public function remove(Participant $participant): int|bool
    {
        return (int)$this->query->update($this->table)
            ->set(['subscribed' => 0])
            ->where('userId', $participant->getUserId())
            ->where('eventId', $participant->getEventId())
            ->execute();
    }

    public function findByUserId(int $userId): bool|array
    {
        return $this->query->from($this->table)
            ->where('userId', $userId)
            ->fetch();
    }

    public function findByUserIdAndEventId(int $userId, int $eventId): bool|array
    {
        return $this->query->from(($this->table))
            ->where('userId', $userId)
            ->where('eventId', $eventId)
            ->where('subscribed', 1)
            ->fetch();
    }

    public function findActiveParticipantByEvent(int $eventId): array
    {
        return $this->query->from($this->table)
            ->where('eventId', $eventId)
            ->where('subscribed', 1)
            ->where('approved', 1)
            ->where('disqualified', 0)
            ->fetchAll();
    }
}
