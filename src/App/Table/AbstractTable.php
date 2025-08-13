<?php declare(strict_types=1);

namespace ownHackathon\App\Table;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use InvalidArgumentException;
use ReflectionClass;
use ownHackathon\Core\Store\StoreInterface;

use function substr;

class AbstractTable implements StoreInterface
{
    protected string $table;
    protected Query $query;

    public function __construct(Query $query)
    {
        $this->table = substr((new ReflectionClass($this))->getShortName(), 0, -5);
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
