<?php declare(strict_types=1);

namespace ownHackathon\Event\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\AbstractTable;
use ownHackathon\Shared\Infrastructure\Persistence\Table\EventStoreInterface;
use PDOException;

class EventTable extends AbstractTable implements EventStoreInterface
{
    public function __construct(
        protected Query $query,
    ) {
        parent::__construct($query);
    }

    /**
     * @throws DuplicateEntryException|PDOException|Exception
     */
    public function persist(array $data): int
    {
        return $this->executePersist($data, [
            'uuid' => $data['uuid'],
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);
    }
}
