<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\Identity\Api\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use App\Token\Api\TokenLoggerInterface;
use App\Token\Infrastructure\Hydrator\TokenHydrator;
use Core\SharedKernel\Utils\UuidFactory;

use function expect;
use function test;

const TEST_UUID = '019becbe-f952-7b82-82fa-f41f8ae24599';

test('hydrators round trip representative entities', function (): void {
    $data = [
        'id' => 1, 'uuid' => TEST_UUID, 'accountId' => 2, 'name' => 'Team', 'password' => 'hash',
        'email' => 'alice@example.com', 'registeredAt' => '2024-01-01 10:00:00', 'lastActionAt' => null,
        'token' => TEST_UUID, 'tokenType' => 2, 'createdAt' => '2024-01-01 10:00:00',
    ];
    $uuid = new UuidFactory();
    $tokenHydrator = new TokenHydrator($uuid, $this->createMock(TokenLoggerInterface::class));
    expect((new AccountHydrator($uuid, $this->createMock(IdentityLoggerInterface::class)))->hydrate($data)->lastActionAt)->toBeNull()
        ->and($tokenHydrator->extract($tokenHydrator->hydrate($data))['tokenType'])->toBe(2);
});

test('collection hydrators skip invalid persistence rows and log the data issue', function (): void {
    $tokenLogger = $this->createMock(TokenLoggerInterface::class);
    $tokenLogger->expects($this->once())->method('warning');
    $accountLogger = $this->createMock(IdentityLoggerInterface::class);
    $accountLogger->expects($this->once())->method('warning');
    $uuid = new UuidFactory();

    expect((new AccountHydrator($uuid, $accountLogger))->hydrateCollection([
        ['id' => 1, 'uuid' => TEST_UUID, 'name' => 'Alice', 'password' => 'hash', 'email' => 'alice@example.com', 'registeredAt' => '2024-01-01', 'lastActionAt' => null],
        ['id' => 2, 'uuid' => 'invalid', 'name' => 'Broken', 'password' => 'hash', 'email' => 'alice@example.com', 'registeredAt' => '2024-01-01', 'lastActionAt' => null],
    ]))->toHaveCount(1)
        ->and((new TokenHydrator($uuid, $tokenLogger))->hydrateCollection([
            ['id' => 1, 'accountId' => 1, 'tokenType' => 2, 'token' => TEST_UUID, 'createdAt' => '2024-01-01'],
            ['id' => 2, 'accountId' => 1, 'tokenType' => 999, 'token' => TEST_UUID, 'createdAt' => '2024-01-01'],
        ]))->toHaveCount(1);
});
