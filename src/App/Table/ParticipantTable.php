<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;

class ParticipantTable extends AbstractTable implements ParticipantRepository
{
    public function insert(Participant $participant): int|bool
    {
        $values = [
            'userId' => $participant->getUserId(),
            'eventId' => $participant->getEventId(),
            'approved' => $participant->isApproved(),
        ];

        $insertStatus = $this->query->insertInto($this->table, $values)
            ->onDuplicateKeyUpdate(['subscribed' => 1])
            ->execute();

        return $insertStatus !== false ? (int)$insertStatus : false;
    }

    public function remove(Participant $participant): int|bool
    {
        return $this->query->update($this->table)
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
        return $this->query->from($this->table)
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
