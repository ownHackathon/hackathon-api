<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use Envms\FluentPDO\Query;

class ProjectTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Project');
    }

    public function findByParticipantId(int $id): bool|array
    {
        return $this->query->from($this->table)
            ->where('participantId', $id)
            ->fetch();
    }
}
