<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\Participant;
use Envms\FluentPDO\Query;

class ParticipantTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Participant');
    }

    public function insert(Participant $participant): self
    {
        $values = [
            'userId' => $participant->getUserId(),
            'eventId' => $participant->getEventId(),
            'approved' => $participant->isApproved(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
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
            ->fetch();
    }

    public function findActiveParticipantByEvent(int $eventId): array
    {
        return $this->query->from($this->table)
            ->where('eventId', $eventId)
            ->where('approved', 1)
            ->where('disqualified', 0)
            ->fetchAll();
    }
}
