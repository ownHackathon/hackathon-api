<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use ownHackathon\Core\Persistence\Store\AbstractTable;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use PDOException;

final class AccountActivationTable extends AbstractTable implements AccountActivationStoreInterface
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
            'token' => $data['token'],
        ]);
    }
}
