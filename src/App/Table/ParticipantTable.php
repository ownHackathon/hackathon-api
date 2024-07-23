<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Core\Exception\DuplicateEntryException;
use Core\Table\AbstractTable;

readonly class ParticipantTable extends AbstractTable implements ParticipantRepository
{
    public function insert(Participant $participant): int
    {
        $values = [
            'userId' => $participant->userId,
            'eventId' => $participant->eventId,
        ];

        $insertStatus = $this->query->insertInto($this->table, $values)
            ->onDuplicateKeyUpdate(['subscribed' => 1])
            ->execute();

        if (!$insertStatus) {
            throw new DuplicateEntryException('Participant', (string)$participant->id);
        }

        return (int)$insertStatus;
    }

    public function remove(Participant $participant): bool
    {
        return (bool)$this->query->update($this->table)
            ->set(['subscribed' => 0])
            ->where('userId', $participant->userId)
            ->where('eventId', $participant->eventId)
            ->execute();
    }

    public function findByUserId(int $userId): array
    {
        $result = $this->query->from($this->table)
            ->where('userId', $userId)
            ->fetch();

        return $result ?: [];
    }

    public function findUserForAnEvent(int $userId, int $eventId): array
    {
        $result = $this->query->from($this->table)
            ->where('userId', $userId)
            ->where('eventId', $eventId)
            ->where('subscribed', 1)
            ->fetch();

        return $result ?: [];
    }

    public function findActiveParticipantsByEvent(int $eventId): array
    {
        $result = $this->query->from($this->table)
            ->where('eventId', $eventId)
            ->where('subscribed', 1)
            ->where('disqualified', 0)
            ->fetchAll();

        return $result ?: [];
    }
}
