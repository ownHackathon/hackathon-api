<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Persistence\Table;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Core\Shared\Infrastructure\Persistence\AbstractTable;
use InvalidArgumentException;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
use ownHackathon\Shared\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Shared\Infrastructure\ValueObject\Pagination;
use PDOException;

use function is_array;
use function sprintf;

class WorkspaceTable extends AbstractTable implements WorkspaceStoreInterface
{
    public function __construct(
        protected Query $query,
        protected readonly WorkspaceHydratorInterface $hydrator
    ) {
        parent::__construct($query);
    }

    /**
     * @throws PDOException
     * @throws DuplicateEntryException|Exception
     */
    public function insert(WorkspaceInterface $data): int
    {
        $value = $this->hydrator->extract($data);

        unset($value['id']);

        try {
            $lastInsertId = $this->query->insertInto($this->table, $value)->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === self::MYSQL_ERROR_DUPLICATED_ENTRY) {
                throw new DuplicateEntryException($this->getTableName(), $data->id);
            }
            throw $e;
        }

        return (int)$lastInsertId;
    }

    public function update(WorkspaceInterface $data): true
    {
        $value = $this->hydrator->extract($data);

        $result = $this->query->update($this->table, $value, $data->id)->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Unknown Error while updating %s with id: %s', $this->getTableName(), $data->id)
            );
        }

        return true;
    }

    public function findById(int $id): ?WorkspaceInterface
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByAccountId(int $accountId, Pagination $pagination): WorkspaceCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('accountId', $accountId)
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function findByName(string $name): ?WorkspaceInterface
    {
        $result = $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findBySlug(string $slug): ?WorkspaceInterface
    {
        $result = $this->query->from($this->table)
            ->where('slug', $slug)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findAll(): WorkspaceCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function countByAccount(int $accountId): int
    {
        $result = $this->query->from($this->table)
            ->where('accountId', $accountId)
            ->count();

        return (int)$result;
    }
}
