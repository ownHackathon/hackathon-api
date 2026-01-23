<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use App\Entity\Account\AccountCollection;
use App\Hydrator\Account\AccountHydrator;
use App\Table\Account\AccountTable;
use Core\Entity\Account\AccountInterface;
use Core\Exception\DuplicateEntryException;
use Core\Store\Account\AccountStoreInterface;
use Core\Type\Email;
use Core\Utils\UuidFactory;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Database\MockQuery;

class MockAccountTable extends AccountTable implements AccountStoreInterface
{
    public function __construct()
    {
        parent::__construct(
            new MockQuery(),
            new AccountHydrator(new UuidFactory()),
        );
    }

    public function getTableName(): string
    {
        return 'Account';
    }

    public function insert(AccountInterface $data): int
    {
        if ($data->id !== Account::ID) {
            throw new DuplicateEntryException('Account', $data->id);
        }

        return 1;
    }

    public function update(AccountInterface $data): true
    {
        if ($data->id !== Account::ID) {
            throw new InvalidArgumentException();
        }

        return true;
    }

    public function deleteById(int $id): true
    {
        if ($id !== Account::ID) {
            throw new InvalidArgumentException();
        }

        return true;
    }

    public function findById(int $id): ?AccountInterface
    {
        return $id === Account::ID ? $this->hydrator->hydrate(Account::VALID_DATA) : null;
    }

    public function findByUuid(UuidInterface $uuid): ?AccountInterface
    {
        return $uuid->toString() === Account::UUID ? $this->hydrator->hydrate(Account::VALID_DATA) : null;
    }

    public function findByName(string $name): ?AccountInterface
    {
        return $name === Account::NAME ? $this->hydrator->hydrate(Account::VALID_DATA) : null;
    }

    public function findByEmail(Email $email): ?AccountInterface
    {
        return $email->toString() === Account::EMAIL ? $this->hydrator->hydrate(Account::VALID_DATA) : null;
    }

    public function findAll(): AccountCollection
    {
        return $this->hydrator->hydrateCollection([Account::VALID_DATA]);
    }
}
