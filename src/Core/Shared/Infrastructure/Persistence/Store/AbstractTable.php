<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Persistence\Store;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use InvalidArgumentException;
use ownHackathon\Shared\Infrastructure\ValueObject\Pagination;
use PDOException;
use ReflectionClass;

use function array_key_first;
use function is_array;
use function substr;

abstract class AbstractTable implements StoreInterface
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
     * @throws InvalidArgumentException|Exception
     */
    public function update(int $id, array $data): true
    {
        $result = $this->query->update($this->table, $data, $id)->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Unknown Error while updating %s with id: %s', $this->getTableName(), $data['id'])
            );
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function fetchOne(array $condition): ?array
    {
        $query = $this->query->from($this->table);

        foreach ($condition as $key => $value) {
            $query = $query->where($key, $value);
        }

        $data = $query->fetch();

        return is_array($data) ? $data : null;
    }

    /**
     * @throws Exception
     */
    public function fetchMany(array $condition, ?Pagination $pagination = null): array
    {
        $query = $this->query->from($this->table);

        foreach ($condition as $key => $value) {
            $query = $query->where($key, $value);
        }

        if ($pagination !== null) {
            $query = $query->limit($pagination->limit)
                ->offset($pagination->offset);
        }

        return $query->fetchAll() ?: [];
    }

    /**
     * @throws Exception
     */
    public function fetchAll(): array
    {
        return $this->query->from($this->table)->fetchAll();
    }

    /**
     * @throws Exception
     */
    public function count(array $condition): int
    {
        $query = $this->query->from($this->table);

        foreach ($condition as $key => $value) {
            $query = $query->where($key, $value);
        }

        return $query->count();
    }

    /**
     * @throws Exception
     */
    public function remove(array $condition): true
    {
        $key = array_key_first($condition);
        $value = $condition[$key];
        $result = $this->query->delete($this->table)
            ->where($key, $value)
            ->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Failed to delete %s table with %s: `%s`', $this->getTableName(), $key, $value)
            );
        }

        return true;
    }

    /**
     * @throws DuplicateEntryException|PDOException|Exception
     */
    protected function executePersist(array $data, array $conflictIdentifier = []): int
    {
        unset($data['id']);

        try {
            return (int)$this->query->insertInto($this->table, $data)->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === self::MYSQL_ERROR_DUPLICATED_ENTRY) {
                throw new DuplicateEntryException($this->getTableName(), $conflictIdentifier);
            }
            throw $e;
        }
    }
}
