<?php declare(strict_types=1);

namespace App\Table\Token;

use App\Hydrator\Token\TokenHydratorInterface;
use App\Table\AbstractTable;
use Core\Entity\Token\TokenCollectionInterface;
use Core\Entity\Token\TokenInterface;
use Core\Exception\DuplicateEntryException;
use Core\Store\Token\TokenStoreInterface;
use Envms\FluentPDO\Query;
use InvalidArgumentException;
use PDOException;

use function is_array;
use function sprintf;

class TokenTable extends AbstractTable implements TokenStoreInterface
{
    public function __construct(
        protected Query $query,
        protected readonly TokenHydratorInterface $hydrator,
    ) {
        parent::__construct($query);
    }

    public function insert(TokenInterface $data): true
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

    public function update(TokenInterface $data): true
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

    public function findById(int $id): ?TokenInterface
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByAccountId(int $accountId): TokenCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('accountId', $accountId)
            ->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function findByToken(string $token): ?TokenInterface
    {
        $result = $this->query->from($this->table)
            ->where('token', $token)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findAll(): TokenCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function deleteByAccountId(int $accountId): true
    {
        $result = $this->query->delete($this->table)
            ->where('accountId', $accountId)
            ->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Failed to delete %s table with accountId: `%s`', $this->getTableName(), $accountId)
            );
        }

        return true;
    }
}
