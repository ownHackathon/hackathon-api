<?php declare(strict_types=1);

namespace Tests\Unit;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\DTO\Account\Account as AccountDto;
use ownHackathon\App\Account\Identity\DTO\Account\AccountRegistration;
use ownHackathon\App\Account\Identity\DTO\Client\ClientIdentificationData;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydrator;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Token\Infrastructure\Hydrator\TokenHydrator;
use ownHackathon\App\Workspace\DTO\PaginationMeta;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;
use ownHackathon\App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\Core\SharedKernel\Utils\UuidFactory;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

const TEST_UUID = '019becbe-f952-7b82-82fa-f41f8ae24599';

test('DTO factories map input values', function (): void {
    expect((array) AccountRegistration::fromString('Alice', 'secret'))
        ->toBe(['accountName' => 'Alice', 'password' => 'secret'])
        ->and(ClientIdentificationData::create(null, 'agent')->ident)->toBe('unsecure')
        ->and(WorkspaceRequest::fromArray(['name' => 'Team', 'visibility' => '700'])->visibility)->toBe(700)
        ->and(PaginationMeta::fromValues(10, 2, 1))->toEqual(new PaginationMeta(1, 2, 10));
});

test('account DTO formats domain dates', function (): void {
    $account = new \ownHackathon\App\Account\Identity\Domain\Account(
        id: 1,
        uuid: Uuid::fromString(TEST_UUID),
        name: 'Alice',
        password: 'hash',
        email: new EmailType('alice@example.com'),
        registeredAt: new DateTimeImmutable('2024-01-02 03:04:05'),
        lastActionAt: new DateTimeImmutable('2024-01-03 04:05:06'),
    );
    $dto = AccountDto::createFromAccount($account);
    expect($dto->registeredAt)->toBe('2024-01-02 03:04:05')->and($dto->email)->toBe('alice@example.com');
});

test('hydrators round trip representative entities', function (): void {
    $data = [
        'id' => 1, 'uuid' => TEST_UUID, 'accountId' => 2, 'name' => 'Team', 'password' => 'hash',
        'email' => 'alice@example.com', 'registeredAt' => '2024-01-01 10:00:00', 'lastActionAt' => null,
        'slug' => 'team', 'description' => 'Description', 'details' => null, 'visibility' => 700,
        'createdAt' => '2024-01-01 10:00:00', 'updatedAt' => '2024-01-02 10:00:00',
        'workspaceId' => 3, 'topicId' => null, 'status' => 1,
        'beginsOn' => '2024-01-03 10:00:00', 'endsOn' => '2024-01-04 10:00:00',
        'token' => TEST_UUID, 'tokenType' => 2,
    ];
    $uuid = new UuidFactory();
    $workspaceHydrator = new WorkspaceHydrator($uuid);
    $tokenHydrator = new TokenHydrator($uuid);
    expect((new AccountHydrator($uuid))->hydrate($data)->lastActionAt)->toBeNull()
        ->and($workspaceHydrator->extract($workspaceHydrator->hydrate($data))['slug'])->toBe('team')
        ->and($tokenHydrator->extract($tokenHydrator->hydrate($data))['tokenType'])->toBe(2)
        ->and((new EventHydrator($uuid))->hydrateCollection([$data]))->toHaveCount(1);
});
