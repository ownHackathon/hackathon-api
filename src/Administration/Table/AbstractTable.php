<?php declare(strict_types=1);

namespace Administration\Table;

use Envms\FluentPDO\Query;

class AbstractTable
{
    public function __construct(
        protected Query $query,
        protected string $table
    ) {
    }

    public function findById(int $id): bool|array
    {
        return $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();
    }

    public function findByName(string $name): bool|array
    {
        return $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();
    }
}
