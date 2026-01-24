<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Persistence\Table\Account;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use Exdrals\Identity\Domain\AccountCollectionInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountHydratorInterface;
use Exdrals\Mailing\Domain\EmailType;
use InvalidArgumentException;
use PDOException;
use Ramsey\Uuid\UuidInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Infrastructure\Persistence\AbstractTable;

use function is_array;
use function sprintf;

class AccountTable extends AbstractTable implements AccountStoreInterface
{
    public function __construct(
        protected Query $query,
        protected readonly AccountHydratorInterface $hydrator
    ) {
        parent::__construct($query);
    }

    /**
     * @throws Exception
     * @throws DuplicateEntryException
     */
    public function insert(AccountInterface $data): int
    {
        $value = $this->hydrator->extract($data);

        unset($value['id']);


        try {
            $lastInserId = $this->query->insertInto($this->table, $value)->execute();
        } catch (PDOException $e) {
            throw new DuplicateEntryException($this->getTableName(), $data->id);
        }

        return (int)$lastInserId;
    }

    public function update(AccountInterface $data): true
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

    public function findById(int $id): ?AccountInterface
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByUuid(UuidInterface $uuid): ?AccountInterface
    {
        $result = $this->query->from($this->table)
            ->where('uuid', $uuid->toString())
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByName(string $name): ?AccountInterface
    {
        $result = $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByEmail(EmailType $email): ?AccountInterface
    {
        $result = $this->query->from($this->table)
            ->where('email', $email->toString())
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findAll(): AccountCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result) ? $this->hydrator->hydrateCollection($result) : $this->hydrator->hydrateCollection([]);
    }
}
