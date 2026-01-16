<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Table;

use InvalidArgumentException;
use ownHackathon\App\Hydrator\AccountAccessAuthHydrator;
use ownHackathon\App\Table\AccountAccessAuthTable;
use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Store\AccountAccessAuthStoreInterface;
use ownHackathon\UnitTest\Mock\Constants\Account;
use ownHackathon\UnitTest\Mock\Constants\AccountAccessAuth;
use ownHackathon\UnitTest\Mock\Database\MockQuery;

class MockAccountAccessAuthTable extends AccountAccessAuthTable implements AccountAccessAuthStoreInterface
{
    public function __construct()
    {
        parent::__construct(
            new MockQuery(),
            new AccountAccessAuthHydrator(),
        );
    }

    public function getTableName(): string
    {
        return 'AccountAccessAuth';
    }

    public function insert(AccountAccessAuthInterface $data): true
    {
        if ($data->getUserId() !== Account::ID) {
            throw new DuplicateEntryException('AccountAccessAuth', $data->getId());
        }

        return true;
    }

    public function update(AccountAccessAuthInterface $data): true
    {
        if ($data->getId() !== AccountAccessAuth::ID) {
            throw new InvalidArgumentException();
        }

        return true;
    }

    public function deleteById(int $id): true
    {
        if ($id !== AccountAccessAuth::ID) {
            throw new InvalidArgumentException();
        }

        return true;
    }

    public function findById(int $id): ?AccountAccessAuthInterface
    {
        return $id === AccountAccessAuth::ID ? $this->hydrator->hydrate(AccountAccessAuth::VALID_DATA) : null;
    }

    public function findByUserId(int $userId): AccountAccessAuthCollectionInterface
    {
        return $userId === AccountAccessAuth::USER_ID
            ? $this->hydrator->hydrateCollection([0 => AccountAccessAuth::VALID_DATA])
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByLabel(string $label): AccountAccessAuthCollectionInterface
    {
        return $label === AccountAccessAuth::LABEL
            ? $this->hydrator->hydrateCollection([0 => AccountAccessAuth::VALID_DATA])
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByRefreshToken(string $refreshToken): ?AccountAccessAuthInterface
    {
        return $refreshToken === AccountAccessAuth::REFRESH_TOKEN ? $this->hydrator->hydrate(
            AccountAccessAuth::VALID_DATA
        ) : null;
    }

    public function findByUserAgent(string $userAgent): AccountAccessAuthCollectionInterface
    {
        return $userAgent === AccountAccessAuth::USER_AGENT
            ? $this->hydrator->hydrateCollection([0 => AccountAccessAuth::VALID_DATA])
            : $this->hydrator->hydrateCollection(
                []
            );
    }

    public function findByClientIdentHash(string $clientIdentHash): ?AccountAccessAuthInterface
    {
        return $clientIdentHash === AccountAccessAuth::CLIENT_IDENT_HASH ? $this->hydrator->hydrate(
            AccountAccessAuth::VALID_DATA
        ) : null;
    }

    public function findAll(): AccountAccessAuthCollectionInterface
    {
        return $this->hydrator->hydrateCollection([0 => AccountAccessAuth::VALID_DATA]);
    }
}
