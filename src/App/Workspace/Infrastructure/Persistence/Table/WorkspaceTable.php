<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Persistence\Table;

use App\Workspace\Domain\WorkspaceCollectionInterface;
use App\Workspace\Domain\WorkspaceInterface;
use App\Workspace\Infrastructure\Hydrator\Workspace\WorkspaceHydratorInterface;
use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use InvalidArgumentException;
use PDOException;
use Shared\Domain\Exception\DuplicateEntryException;
use Shared\Infrastructure\Persistence\AbstractTable;

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
     * @throws Exception
     * @throws DuplicateEntryException
     */
    public function insert(WorkspaceInterface $data): true
    {
        $value = $this->hydrator->extract($data);

        unset($value['id']);

        try {
            $this->query->insertInto($this->table, $value)->execute();
        } catch (PDOException $e) {
            throw new DuplicateEntryException($this->getTableName(), $data->id);
        }

        return true;
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

    public function findByName(string $name): ?WorkspaceInterface
    {
        $result = $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByAccountId(int $accountId): WorkspaceCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('accountId', $accountId)
            ->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function findAll(): WorkspaceCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }
}
