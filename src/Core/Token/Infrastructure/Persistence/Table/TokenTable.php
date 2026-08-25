<?php declare(strict_types=1);

namespace Exdrals\Core\Token\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Query;
use Exception;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\AbstractTable;
use Exdrals\Core\Shared\Infrastructure\Persistence\Store\Token\TokenStoreInterface;
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
