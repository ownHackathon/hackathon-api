<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Persistence\Table;

use Core\Persistence\Store\AbstractTable;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Envms\FluentPDO\Query;
use Exception;
use PDOException;

final class EventTable extends AbstractTable implements EventStoreInterface
{
    public function __construct(
        protected Query $query,
    ) {
        parent::__construct($query);
    }

    /**
     * @throws DuplicateEntryException|PDOException|Exception
     */
    #[\Override]
    public function persist(array $data): int
    {
        return $this->executePersist($data, [
            'uuid' => $data['uuid'],
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);
    }
}
