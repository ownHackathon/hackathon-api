<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Hydrator;

use PHPUnit\Framework\TestCase;
use ownHackathon\App\Hydrator\AccountHydrator;
use ownHackathon\Core\Entity\Account\AccountCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Utils\UuidFactory;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use ownHackathon\UnitTest\Mock\Constants\Account;

class AccountHydratorTest extends TestCase
{
    private UuidFactoryInterface $uuidFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->uuidFactory = new UuidFactory();
    }

    public function testCanHydrateAccount(): void
    {
        $hydrator = new AccountHydrator($this->uuidFactory);

        $account = $hydrator->hydrate(Account::VALID_DATA);

        $this->assertInstanceOf(AccountInterface::class, $account);
        $this->assertSame(Account::ID, $account->id);
    }

    public function testCanHydrateAccountCollection(): void
    {
        $hydrator = new AccountHydrator($this->uuidFactory);

        /** @var AccountInterface[] $accounts | [] */
        $accounts = $hydrator->hydrateCollection([Account::VALID_DATA]);

        $this->assertInstanceOf(AccountCollectionInterface::class, $accounts);
        $this->assertInstanceOf(AccountInterface::class, $accounts[0]);
        $this->assertSame(Account::ID, $accounts[0]->id);
    }

    public function testCanExtractAccount(): void
    {
        $hydrator = new AccountHydrator($this->uuidFactory);

        $account = $hydrator->hydrate(Account::VALID_DATA);
        $account = $hydrator->extract($account);

        $this->assertIsArray($account);
        $this->assertSame(Account::VALID_DATA, $account);
    }

    public function testCanExtractAccountCollection(): void
    {
        $hydrator = new AccountHydrator($this->uuidFactory);
        $accounts = $hydrator->hydrateCollection([Account::VALID_DATA]);
        $accounts = $hydrator->extractCollection($accounts);

        $this->assertIsArray($accounts);
        $this->assertArrayHasKey(0, $accounts);
        $this->assertSame(Account::VALID_DATA, $accounts[0]);
    }
}
