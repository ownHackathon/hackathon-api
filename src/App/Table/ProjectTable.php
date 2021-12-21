<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;

class ProjectTable extends AbstractTable
{
    public function findByParticipantId(int $id): bool|array
    {
        return $this->query->from($this->table)
            ->where('participantId', $id)
            ->fetch();
    }
}
