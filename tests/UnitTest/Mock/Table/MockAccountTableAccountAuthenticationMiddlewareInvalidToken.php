<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use InvalidArgumentException;
use App\Entity\Account\AccountCollection;
use App\Hydrator\AccountHydrator;
use App\Table\AccountTable;
use Core\Entity\Account\AccountInterface;
use Core\Exception\DuplicateEntryException;
use Core\Store\AccountStoreInterface;
use Core\Type\Email;
use Core\Utils\UuidFactory;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Database\MockQuery;
use Ramsey\Uuid\UuidInterface;

class MockAccountTableAccountAuthenticationMiddlewareInvalidToken extends AccountTable implements AccountStoreInterface
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

    public function insert(AccountInterface $data): true
    {
        if ($data->id !== Account::ID) {
            throw new DuplicateEntryException('Account', $data->id);
        }

        return true;
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
        return null;
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
