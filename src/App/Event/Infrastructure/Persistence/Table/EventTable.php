<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use ownHackathon\Core\Persistence\Store\AbstractTable;
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
