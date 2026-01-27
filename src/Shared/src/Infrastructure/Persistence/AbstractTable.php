<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Persistence;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use Exdrals\Shared\Infrastructure\Persistence\Store\StoreInterface;
use InvalidArgumentException;
use ReflectionClass;

use function substr;

class AbstractTable implements StoreInterface
{
    public const int MYSQL_ERROR_DUPLICATED_ENTRY = 1062;

    protected string $table;
    protected Query $query;

    public function __construct(Query $query)
    {
        $this->table = substr(new ReflectionClass($this)->getShortName(), 0, -5);
        $this->query = $query;
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * @throws Exception
     */
    public function deleteById(int $id): true
    {
        $result = $this->query->delete($this->table, $id)->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Failed to delete %s table with id: `%s`', $this->getTableName(), $id)
            );
        }

        return true;
    }
}
