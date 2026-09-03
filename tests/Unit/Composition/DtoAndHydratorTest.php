<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use App\Account\Identity\DTO\Account\Account as AccountDto;
use App\Account\Identity\DTO\Account\AccountRegistration;
use App\Account\Identity\DTO\Client\ClientIdentificationData;
use App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use App\Event\Application\Port\EventLoggerInterface;
use App\Event\Infrastructure\Hydrator\EventHydrator;
use App\Mailing\Domain\EmailType;
use App\Policy\Domain\Enum\Visibility;
use App\Token\Application\Port\TokenLoggerInterface;
use App\Token\Infrastructure\Hydrator\TokenHydrator;
use App\Workspace\Application\Port\WorkspaceLoggerInterface;
use App\Workspace\DTO\PaginationMeta;
use App\Workspace\DTO\WorkspaceRequest;
use App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use Core\SharedKernel\Utils\UuidFactory;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

const TEST_UUID = '019becbe-f952-7b82-82fa-f41f8ae24599';

test('DTO factories map input values', function (): void {
    expect((array) AccountRegistration::fromString('Alice', 'secret'))
        ->toBe(['accountName' => 'Alice', 'password' => 'secret'])
        ->and(ClientIdentificationData::create(null, 'agent')->ident)->toBe('unsecure')
        ->and(WorkspaceRequest::fromArray(['name' => 'Team', 'visibility' => (string) Visibility::PUBLIC->value])->visibility)->toBe(Visibility::PUBLIC->value)
        ->and(PaginationMeta::fromValues(10, 2, 1))->toEqual(new PaginationMeta(1, 2, 10));
});

test('account DTO formats domain dates', function (): void {
    $account = new \App\Account\Identity\Domain\Account(
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
        'slug' => 'team', 'description' => 'Description', 'details' => null, 'visibility' => Visibility::PUBLIC->value,
        'createdAt' => '2024-01-01 10:00:00', 'updatedAt' => '2024-01-02 10:00:00',
        'workspaceId' => 3, 'topicId' => null, 'status' => 1,
        'beginsOn' => '2024-01-03 10:00:00', 'endsOn' => '2024-01-04 10:00:00',
        'token' => TEST_UUID, 'tokenType' => 2,
    ];
    $uuid = new UuidFactory();
    $workspaceHydrator = new WorkspaceHydrator($uuid, $this->createMock(WorkspaceLoggerInterface::class));
    $tokenHydrator = new TokenHydrator($uuid, $this->createMock(TokenLoggerInterface::class));
    expect((new AccountHydrator($uuid, $this->createMock(IdentityLoggerInterface::class)))->hydrate($data)->lastActionAt)->toBeNull()
        ->and($workspaceHydrator->extract($workspaceHydrator->hydrate($data))['slug'])->toBe('team')
        ->and($tokenHydrator->extract($tokenHydrator->hydrate($data))['tokenType'])->toBe(2)
        ->and((new EventHydrator($uuid, $this->createMock(EventLoggerInterface::class)))->hydrateCollection([$data]))->toHaveCount(1);
});

test('workspace hydrator falls back to unlisted for invalid visibility values', function (mixed $invalidVisibility): void {
    $data = [
        'id' => 1,
        'uuid' => TEST_UUID,
        'accountId' => 2,
        'name' => 'Team',
        'slug' => 'team',
        'description' => null,
        'details' => null,
        'visibility' => $invalidVisibility,
        'createdAt' => '2024-01-01 10:00:00',
        'updatedAt' => '2024-01-02 10:00:00',
    ];

    $workspace = (new WorkspaceHydrator(new UuidFactory(), $this->createMock(WorkspaceLoggerInterface::class)))->hydrate($data);

    expect($workspace->visibility)->toBe(Visibility::UNLISTED);
})->with([
    'legacy value' => Visibility::PUBLIC->value + 1,
    'null value' => null,
    'invalid string' => 'invalid',
]);

test('workspace hydrator falls back to unlisted when visibility is missing', function (): void {
    $data = [
        'id' => 1,
        'uuid' => TEST_UUID,
        'accountId' => 2,
        'name' => 'Team',
        'slug' => 'team',
        'description' => null,
        'details' => null,
        'createdAt' => '2024-01-01 10:00:00',
        'updatedAt' => '2024-01-02 10:00:00',
    ];

    $workspace = (new WorkspaceHydrator(new UuidFactory(), $this->createMock(WorkspaceLoggerInterface::class)))->hydrate($data);

    expect($workspace->visibility)->toBe(Visibility::UNLISTED);
});

test('event hydrator falls back to unlisted for invalid visibility values', function (): void {
    $data = [
        'id' => 1,
        'uuid' => TEST_UUID,
        'workspaceId' => 2,
        'topicId' => null,
        'name' => 'Event',
        'slug' => 'event',
        'description' => null,
        'details' => null,
        'status' => 1,
        'visibility' => Visibility::PUBLIC->value + 1,
        'beginsOn' => '2024-01-03 10:00:00',
        'endsOn' => '2024-01-04 10:00:00',
        'createdAt' => '2024-01-01 10:00:00',
    ];

    $event = (new EventHydrator(new UuidFactory(), $this->createMock(EventLoggerInterface::class)))->hydrate($data);

    expect($event->visibility)->toBe(Visibility::UNLISTED);
});

test('collection hydrators skip invalid persistence rows and log the data issue', function (): void {
    $workspaceLogger = $this->createMock(WorkspaceLoggerInterface::class);
    $workspaceLogger->expects($this->once())->method('warning');
    $eventLogger = $this->createMock(EventLoggerInterface::class);
    $eventLogger->expects($this->once())->method('warning');
    $accountLogger = $this->createMock(IdentityLoggerInterface::class);
    $accountLogger->expects($this->once())->method('warning');
    $tokenLogger = $this->createMock(TokenLoggerInterface::class);
    $tokenLogger->expects($this->once())->method('warning');
    $uuid = new UuidFactory();

    $workspaceData = [
        'id' => 1, 'uuid' => TEST_UUID, 'accountId' => 2, 'name' => 'Team', 'slug' => 'team',
        'description' => null, 'details' => null, 'visibility' => Visibility::PUBLIC->value,
        'createdAt' => '2024-01-01 10:00:00', 'updatedAt' => '2024-01-02 10:00:00',
    ];
    $eventData = [
        'id' => 1, 'uuid' => TEST_UUID, 'workspaceId' => 2, 'topicId' => null, 'name' => 'Event',
        'slug' => 'event', 'description' => null, 'details' => null, 'status' => 1,
        'visibility' => Visibility::PUBLIC->value, 'beginsOn' => '2024-01-03 10:00:00',
        'endsOn' => '2024-01-04 10:00:00', 'createdAt' => '2024-01-01 10:00:00',
    ];

    expect((new WorkspaceHydrator($uuid, $workspaceLogger))->hydrateCollection([
        $workspaceData,
        [...$workspaceData, 'id' => 2, 'uuid' => 'invalid'],
    ]))->toHaveCount(1)
        ->and((new EventHydrator($uuid, $eventLogger))->hydrateCollection([
            $eventData,
            [...$eventData, 'id' => 2, 'status' => 999],
        ]))->toHaveCount(1)
        ->and((new AccountHydrator($uuid, $accountLogger))->hydrateCollection([
            ['id' => 1, 'uuid' => TEST_UUID, 'name' => 'Alice', 'password' => 'hash', 'email' => 'alice@example.com', 'registeredAt' => '2024-01-01', 'lastActionAt' => null],
            ['id' => 2, 'uuid' => 'invalid', 'name' => 'Broken', 'password' => 'hash', 'email' => 'alice@example.com', 'registeredAt' => '2024-01-01', 'lastActionAt' => null],
        ]))->toHaveCount(1)
        ->and((new TokenHydrator($uuid, $tokenLogger))->hydrateCollection([
            ['id' => 1, 'accountId' => 1, 'tokenType' => 2, 'token' => TEST_UUID, 'createdAt' => '2024-01-01'],
            ['id' => 2, 'accountId' => 1, 'tokenType' => 999, 'token' => TEST_UUID, 'createdAt' => '2024-01-01'],
        ]))->toHaveCount(1);
});
