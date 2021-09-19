<?php
declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use Envms\FluentPDO\Query;

class EventTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Event');
    }

    public function findAll(): bool|array
    {
        return $this->query->from($this->table)->fetchAll();
    }

    public function findAllActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 1)
            ->fetchAll();
    }

    public function findAllNotActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 0)
            ->fetchAll();
    }
}
