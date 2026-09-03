<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Persistence\Table;

use Core\Persistence\Store\AbstractTable;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Envms\FluentPDO\Query;
use Exception;
use PDOException;

final class AccountAccessAuthTable extends AbstractTable implements AccountAccessAuthStoreInterface
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
            'refreshToken' => $data['refreshToken'],
            'clientIdentHash' => $data['clientIdentHash'],
        ]);
    }
}
