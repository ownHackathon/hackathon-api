<?php declare(strict_types=1);

namespace UnitTest\AppTest\Table;

use App\Hydrator\Account\AccountHydrator;
use App\Hydrator\Account\AccountHydratorInterface;
use App\Hydrator\Account\WorkspaceHydrator;
use App\Hydrator\Account\WorkspaceHydratorInterface;
use App\Table\Account\AccountTable;
use Core\Entity\Account\AccountCollectionInterface;
use Core\Entity\Account\AccountInterface;
use Core\Exception\DuplicateEntryException;
use Core\Store\Account\AccountStoreInterface;
use Core\Type\Email;
use Core\Utils\UuidFactory;
use Envms\FluentPDO\Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Database\MockQuery;
use UnitTest\Mock\Database\MockQueryFailed;

class AccountTableTest extends TestCase
{
    private AccountHydratorInterface $hydrator;
    private AccountStoreInterface $table;
    private UuidFactory $uuidFactory;

    protected function setUp(): void
    {
        $query = new MockQuery();
        $this->uuidFactory = new UuidFactory();
        $this->hydrator = new AccountHydrator($this->uuidFactory);
        $this->table = new AccountTable($query, $this->hydrator);
    }

    public function testCanGetTableName(): void
    {
        $this->assertSame('Account', $this->table->getTableName());
    }

    public function testCanInsertAccount(): void
    {
        $account = $this->hydrator->hydrate(Account::VALID_DATA);

        $result = $this->table->insert($account);

        $this->assertIsInt($result);
        $this->assertSame(Account::ID, $result);
    }

    public function testInsertAccountThrowsException(): void
    {
        $table = new AccountTable(new MockQueryFailed(), $this->hydrator);

        $account = $this->hydrator->hydrate(Account::VALID_DATA);

        $this->expectException(DuplicateEntryException::class);

        $table->insert($account);
    }

    public function testCanUpdateAccount(): void
    {
        $account = $this->hydrator->hydrate(Account::VALID_DATA);

        $result = $this->table->update($account);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testUpdateAccountThrowsException(): void
    {
        $table = new AccountTable(new MockQueryFailed(), $this->hydrator);
        $account = $this->hydrator->hydrate(Account::VALID_DATA);

        $this->expectException(InvalidArgumentException::class);

        $table->update($account);
    }

    public function testCanDeleteById(): void
    {
        $result = $this->table->deleteById(Account::ID);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testDeleteAccountThrowsException(): void
    {
        $table = new AccountTable(new MockQueryFailed(), $this->hydrator);

        $this->expectException(InvalidArgumentException::class);

        $table->deleteById(Account::ID);
    }

    /**
     * @throws Exception
     */
    public function testCanFindById(): void
    {
        $account = $this->table->findById(Account::ID);

        $this->assertInstanceOf(AccountInterface::class, $account);
        $this->assertSame(Account::VALID_DATA, $this->hydrator->extract($account));
    }

    /**
     * @throws Exception
     */
    public function testFindByIdIsEmpty(): void
    {
        $result = $this->table->findById(Account::ID_INVALID);

        $this->assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testCanFindByUuid(): void
    {
        $uuid = $this->uuidFactory->fromString(Account::UUID);
        $account = $this->table->findByUuid($uuid);

        $this->assertInstanceOf(AccountInterface::class, $account);
        $this->assertSame(Account::VALID_DATA, $this->hydrator->extract($account));
    }

    /**
     * @throws Exception
     */
    public function testFindByUuidIsEmpty(): void
    {
        $uuid = $this->uuidFactory->fromString(Account::UUID_INVALID);
        $result = $this->table->findByUuid($uuid);

        $this->assertNull($result);
    }

    public function testCanFindByName(): void
    {
        $account = $this->table->findByName(Account::NAME);

        $this->assertInstanceOf(AccountInterface::class, $account);
        $this->assertSame(Account::VALID_DATA, $this->hydrator->extract($account));
    }

    public function testFindByNameIsEmpty(): void
    {
        $result = $this->table->findByName(Account::NAME_INVALID);

        $this->assertNull($result);
    }

    public function testCanFindByEmail(): void
    {
        $account = $this->table->findByEmail(new Email(Account::EMAIL));

        $this->assertInstanceOf(AccountInterface::class, $account);
        $this->assertSame(Account::VALID_DATA, $this->hydrator->extract($account));
    }

    public function testFindByEmailIsEmpty(): void
    {
        $result = $this->table->findByEmail(new Email(Account::EMAIL_INVALID));

        $this->assertNull($result);
    }

    public function testCanFindAllAccount(): void
    {
        $accounts = $this->table->findAll();

        $this->assertInstanceOf(AccountCollectionInterface::class, $accounts);
        $this->assertSame([0 => Account::VALID_DATA], $this->hydrator->extractCollection($accounts));
    }

    public function testFindAllAccountIsEmpty(): void
    {
        $table = new AccountTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findAll();

        $this->assertInstanceOf(AccountCollectionInterface::class, $result);
        $this->assertEmpty($result);
    }
}
