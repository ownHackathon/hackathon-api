<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use App\Hydrator\Account\AccountAccessAuthHydrator;
use App\Table\Account\AccountAccessAuthTable;
use Core\Entity\Account\AccountAccessAuthCollectionInterface;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Exception\DuplicateEntryException;
use Core\Store\Account\AccountAccessAuthStoreInterface;
use InvalidArgumentException;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Constants\AccountAccessAuth;
use UnitTest\Mock\Database\MockQuery;

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
        if ($data->accountId !== Account::ID) {
            throw new DuplicateEntryException('AccountAccessAuth', $data->id);
        }

        return true;
    }

    public function update(AccountAccessAuthInterface $data): true
    {
        if ($data->id !== AccountAccessAuth::ID) {
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

    public function findByAccountId(int $accountId): AccountAccessAuthCollectionInterface
    {
        return $accountId === AccountAccessAuth::USER_ID
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
