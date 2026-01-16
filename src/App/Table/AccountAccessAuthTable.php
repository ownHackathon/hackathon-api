<?php declare(strict_types=1);

namespace ownHackathon\App\Table;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use InvalidArgumentException;
use PDOException;
use ownHackathon\App\Hydrator\AccountAccessAuthHydratorInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Store\AccountAccessAuthStoreInterface;

use function is_array;
use function sprintf;

class AccountAccessAuthTable extends AbstractTable implements AccountAccessAuthStoreInterface
{
    public function __construct(
        protected Query $query,
        protected AccountAccessAuthHydratorInterface $hydrator
    ) {
        parent::__construct($query);
    }

    public function insert(AccountAccessAuthInterface $data): true
    {
        $value = $this->hydrator->extract($data);

        unset($value['id']);

        try {
            $lastInsertId = $this->query->insertInto($this->table, $value)->execute();
        } catch (Exception | PDOException $e) {
            return throw new DuplicateEntryException($this->getTableName(), $data->getId());
        }

        return true;
    }

    public function update(AccountAccessAuthInterface $data): true
    {
        $value = $this->hydrator->extract($data);

        $result = $this->query->update($this->table, $value, $data->getId())->execute();

        if ($result === false) {
            throw new InvalidArgumentException(
                sprintf('Unknown Error while updating %s with id: %s', $this->getTableName(), $data->getId())
            );
        }

        return true;
    }

    public function findById(int $id): ?AccountAccessAuthInterface
    {
        $result = $this->query->from($this->table)
            ->where('id', $id)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByUserId(int $userId): AccountAccessAuthCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('userId', $userId)
            ->fetchAll();

        return is_array($result)
            ? $this->hydrator->hydrateCollection($result)
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('label', $label)
            ->fetchAll();

        return is_array($result)
            ? $this->hydrator->hydrateCollection($result)
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface
    {
        $result = $this->query->from($this->table)
            ->where('refreshToken', $refreshToken)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface
    {
        $result = $this->query->from($this->table)
            ->where('userAgent', $userAgent)
            ->fetchAll();

        return is_array($result)
            ? $this->hydrator->hydrateCollection($result)
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface
    {
        $result = $this->query->from($this->table)
            ->where('clientIdentHash', $clientIdentHash)
            ->fetch();

        return is_array($result) ? $this->hydrator->hydrate($result) : null;
    }

    public function findAll(): AccountAccessAuthCollectionInterface
    {
        $result = $this->query->from($this->table)->fetchAll();

        return is_array($result)
            ? $this->hydrator->hydrateCollection($result)
            : $this->hydrator->hydrateCollection(
                []
            );
    }
}
