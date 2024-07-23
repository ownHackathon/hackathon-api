<?php declare(strict_types=1);

namespace App\Table;

use App\Repository\ProjectRepository;
use Core\Table\AbstractTable;

readonly class ProjectTable extends AbstractTable implements ProjectRepository
{
    public function findByParticipantId(int $id): bool|array
    {
        return $this->query->from($this->table)
            ->where('participantId', $id)
            ->fetch();
    }
}
