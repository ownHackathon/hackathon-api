<?php declare(strict_types=1);

namespace UnitTest\AppTest\Table;

use Exdrals\Identity\Domain\AccountAccessAuthCollection;
use Exdrals\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydrator;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydratorInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthTable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use UnitTest\Mock\Constants\AccountAccessAuth;
use UnitTest\Mock\Database\MockQuery;
use UnitTest\Mock\Database\MockQueryFailed;

class AccountAccessAuthTableTest extends TestCase
{
    private AccountAccessAuthHydratorInterface $hydrator;
    private MockQuery $query;
    private AccountAccessAuthStoreInterface $table;

    protected function setUp(): void
    {
        $this->query = new MockQuery();
        $this->hydrator = new AccountAccessAuthHydrator();
        $this->table = new AccountAccessAuthTable($this->query, $this->hydrator);
    }

    public function testCanGetTableName(): void
    {
        $this->assertSame('AccountAccessAuth', $this->table->getTableName());
    }

    public function testCanInsertAccountAccessAuth(): void
    {
        $accountAccessAuth = $this->hydrator->hydrate(AccountAccessAuth::VALID_DATA);

        $result = $this->table->insert($accountAccessAuth);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testInsertAccountAccessAuthThrowsException(): void
    {
        $accountAccessAuth = $this->hydrator->hydrate(AccountAccessAuth::VALID_DATA);
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $this->expectException(DuplicateEntryException::class);

        $table->insert($accountAccessAuth);
    }

    public function testCanUpdateAccountAccessAuth(): void
    {
        $accountAccessAuth = $this->hydrator->hydrate(AccountAccessAuth::VALID_DATA);

        $result = $this->table->update($accountAccessAuth);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testUpdateAccountAccessAuthThrowsException(): void
    {
        $accountAccessAuth = $this->hydrator->hydrate(AccountAccessAuth::VALID_DATA);
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $this->expectException(InvalidArgumentException::class);

        $table->update($accountAccessAuth);
    }

    public function testCanDeleteById(): void
    {
        $result = $this->table->deleteById(AccountAccessAuth::ID);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testDeleteAccountThrowsException(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $this->expectException(InvalidArgumentException::class);

        $table->deleteById(AccountAccessAuth::ID);
    }

    public function testCanFindById(): void
    {
        $result = $this->table->findById(AccountAccessAuth::ID);

        $this->assertInstanceOf(AccountAccessAuthInterface::class, $result);
        $this->assertSame(AccountAccessAuth::ID, $result->id);
    }

    public function testFindByIdIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findById(AccountAccessAuth::ID);

        $this->assertNull($result);
    }

    public function testCanFindByUserId(): void
    {
        /** @var AccountAccessAuthCollectionInterface<AccountAccessAuthInterface> $result */
        $result = $this->table->findByAccountId(AccountAccessAuth::USER_ID);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertSame(AccountAccessAuth::USER_ID, $result[0]->accountId);
    }

    public function testFindByUserIdIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findByAccountId(AccountAccessAuth::USER_ID);

        $this->assertInstanceOf(AccountAccessAuthCollection::class, $result);
        $this->assertEmpty($result);
    }

    public function testCanFindByLabel(): void
    {
        /** @var AccountAccessAuthCollectionInterface<AccountAccessAuthInterface> $result */
        $result = $this->table->findByLabel(AccountAccessAuth::LABEL);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertSame(AccountAccessAuth::LABEL, $result[0]->label);
    }

    public function testFindByLabelIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findByLabel(AccountAccessAuth::LABEL);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertEmpty($result);
    }

    public function testCanFindByRefreshToken(): void
    {
        /** @var AccountAccessAuthInterface $result */
        $result = $this->table->findByRefreshToken(AccountAccessAuth::REFRESH_TOKEN);

        $this->assertInstanceOf(AccountAccessAuthInterface::class, $result);
        $this->assertSame(AccountAccessAuth::REFRESH_TOKEN, $result->refreshToken);
    }

    public function testFindByRefreshTokenIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findByRefreshToken(AccountAccessAuth::REFRESH_TOKEN);

        $this->assertNull($result);
    }

    public function testCanFindByUserAgent(): void
    {
        /** @var AccountAccessAuthCollectionInterface<AccountAccessAuthInterface> $result */
        $result = $this->table->findByUserAgent(AccountAccessAuth::USER_AGENT);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertSame(AccountAccessAuth::USER_AGENT, $result[0]->userAgent);
    }

    public function testFindByUserAgentIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findByUserAgent(AccountAccessAuth::USER_AGENT);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertEmpty($result);
    }

    public function testCanFindByClientIdentHash(): void
    {
        /** @var AccountAccessAuthInterface $result */
        $result = $this->table->findByClientIdentHash(AccountAccessAuth::CLIENT_IDENT_HASH);

        $this->assertInstanceOf(AccountAccessAuthInterface::class, $result);
        $this->assertSame(AccountAccessAuth::CLIENT_IDENT_HASH, $result->clientIdentHash);
    }

    public function testFindByClientIdentHashIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findByClientIdentHash(AccountAccessAuth::CLIENT_IDENT_HASH);

        $this->assertNull($result);
    }

    public function testCanFindAll(): void
    {
        /** @var AccountAccessAuthCollectionInterface<AccountAccessAuthInterface> $result */
        $result = $this->table->findAll();

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertSame(AccountAccessAuth::ID, $result[0]->id);
    }

    public function testFindAllIsEmpty(): void
    {
        $table = new AccountAccessAuthTable(new MockQueryFailed(), $this->hydrator);

        $result = $table->findAll();

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $result);
        $this->assertEmpty($result);
    }
}
