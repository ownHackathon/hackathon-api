<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use ownHackathon\Core\Persistence\Store\AbstractTable;
use PDOException;

class TokenTable extends AbstractTable implements TokenStoreInterface
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
