<?php declare(strict_types=1);

namespace UnitTest\AppTest\Hydrator;

use Exdrals\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Account\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydrator;
use PHPUnit\Framework\TestCase;
use UnitTest\Mock\Constants\AccountAccessAuth;

class AccountAccessAuthHydratorTest extends TestCase
{
    public function testCanHydrateAccountAccessAuth(): void
    {
        $hydrator = new AccountAccessAuthHydrator();

        $account = $hydrator->hydrate(AccountAccessAuth::VALID_DATA);

        $this->assertInstanceOf(AccountAccessAuthInterface::class, $account);
        $this->assertSame(AccountAccessAuth::ID, $account->id);
    }

    public function testCanHydrateAccountAccessAuthCollection(): void
    {
        $hydrator = new AccountAccessAuthHydrator();

        $accounts = $hydrator->hydrateCollection([AccountAccessAuth::VALID_DATA]);

        $this->assertInstanceOf(AccountAccessAuthCollectionInterface::class, $accounts);
        $this->assertInstanceOf(AccountAccessAuthInterface::class, $accounts[0]);
        $this->assertSame(AccountAccessAuth::ID, $accounts[0]->id);
    }

    public function testCanExtractAccountAccessAuth(): void
    {
        $hydrator = new AccountAccessAuthHydrator();

        $account = $hydrator->hydrate(AccountAccessAuth::VALID_DATA);
        $account = $hydrator->extract($account);

        $this->assertIsArray($account);
        $this->assertSame(AccountAccessAuth::VALID_DATA, $account);
    }

    public function testCanExtractAccountAccessAuthCollection(): void
    {
        $hydrator = new AccountAccessAuthHydrator();
        $accounts = $hydrator->hydrateCollection([AccountAccessAuth::VALID_DATA]);
        $accounts = $hydrator->extractCollection($accounts);

        $this->assertIsArray($accounts);
        $this->assertArrayHasKey(0, $accounts);
        $this->assertSame(AccountAccessAuth::VALID_DATA, $accounts[0]);
    }
}
