<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account;

use Exdrals\Account\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountActivationInterface;
use Exdrals\Account\Identity\Domain\Email;
use Exdrals\Account\Identity\Infrastructure\Hydrator\Account\AccountActivationHydratorInterface;
use Envms\FluentPDO\Query;
use InvalidArgumentException;
use PDOException;
use Shared\Domain\Exception\DuplicateEntryException;
use Shared\Infrastructure\Persistence\AbstractTable;

use function is_array;
use function sprintf;

class AccountActivationTable extends AbstractTable implements AccountActivationStoreInterface
{
    public function __construct(
        protected Query $query,
        protected readonly AccountActivationHydratorInterface $hydrator,
    ) {
        parent::__construct($query);
    }

    public function insert(AccountActivationInterface $data): true
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

    public function update(AccountActivationInterface $data): true
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

    public function findById(int $id): ?AccountActivationInterface
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByEmail(Email $email): AccountActivationCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('email', $email->toString())
            ->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function findByToken(string $token): ?AccountActivationInterface
    {
        $result = $this->query->from($this->table)
            ->where('token', $token)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findAll(): AccountActivationCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }

    public function deleteByEmail(Email $email): true
    {
        $result = $this->query->delete($this->table)
            ->where('email', $email->toString())
            ->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Failed to delete %s table with email: `%s`', $this->getTableName(), $email->toString())
            );
        }

        return true;
    }
}
