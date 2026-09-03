<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Persistence\Table;

use Core\Persistence\Store\AbstractTable;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Envms\FluentPDO\Query;
use Exception;
use PDOException;

final class TokenTable extends AbstractTable implements TokenStoreInterface
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
            'token' => $data['token'],
        ]);
    }
}
