<?php declare(strict_types=1);

namespace Core\Table;

use Core\Repository\Repository;
use Envms\FluentPDO\Query;
use ReflectionClass;

use function substr;

readonly class AbstractTable implements Repository
{
    protected string $table;

    public function __construct(
        protected Query $query
    ) {
        $this->table = substr((new ReflectionClass($this))->getShortName(), 0, -5);
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function findById(int $id): array
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return $result ?: [];
    }

    public function findAll(): array
    {
        $result = $this->query->from($this->table)->fetchAll();

        return $result ?: [];
    }
}
