<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\AbstractTable;
use PDOException;

class AccountTable extends AbstractTable implements AccountStoreInterface
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
            'email' => $data['email'],
            'name' => $data['name'],
            'uuid' => $data['uuid'],
        ]);
    }
}
