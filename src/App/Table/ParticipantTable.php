<?php
declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use Envms\FluentPDO\Query;

class ParticipantTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Participant');
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
