<?php declare(strict_types=1);

namespace Tests\Integration\Composition;

use DateTimeImmutable;
use InvalidArgumentException;
use App\Account\Identity\DTO\Account\AccountPassword;
use App\Account\Identity\DTO\Account\AccountRegistration;
use App\Account\Identity\DTO\Account\ApiMe;
use App\Account\Identity\DTO\Token\AccountPasswordToken;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthCollection;
use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\AccountActivationCollection;
use App\Account\Identity\Domain\AccountCollection;
use App\Event\Domain\Event;
use App\Event\Domain\EventCollection;
use App\Event\Domain\Enum\EventStatus;
use App\Mailing\Domain\EmailType;
use App\Token\Domain\Enum\TokenType;
use App\Token\Domain\Token;
use App\Token\Domain\TokenCollection;
use App\Policy\Domain\Enum\Visibility;
use App\Workspace\Domain\Workspace;
use App\Workspace\Domain\WorkspaceCollection;
use App\Workspace\DTO\PaginationMeta;
use App\Workspace\DTO\Workspace as WorkspaceDto;
use App\Workspace\DTO\WorkspaceList;
use App\Workspace\DTO\WorkspaceRequest;
use App\Workspace\DTO\WorkspaceResponse;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function accountFixture(): Account
{
    return new Account(
        id: 1,
        uuid: Uuid::uuid4(),
        name: 'Test User',
        password: 'password',
        email: EmailType::fromString('test@example.com'),
        registeredAt: new DateTimeImmutable('2024-01-01'),
        lastActionAt: new DateTimeImmutable('2024-01-02'),
    );
}

function workspaceFixture(): Workspace
{
    return new Workspace(
        id: 1,
        uuid: Uuid::uuid4(),
        accountId: 1,
        name: 'Test Workspace',
        slug: 'test-workspace',
        description: 'Description',
        details: null,
        visibility: Visibility::PUBLIC,
        createdAt: new DateTimeImmutable('2024-01-01'),
        updatedAt: new DateTimeImmutable('2024-01-02'),
    );
}

test('DTO factories create their expected values', function () {
    $account = accountFixture();
    $workspace = workspaceFixture();

    expect(AccountPassword::fromString('secret')->password)->toBe('secret')
        ->and(AccountPasswordToken::fromString('token')->accountPasswordToken)->toBe('token')
        ->and(AccountRegistration::fromString('User', 'secret'))
        ->toMatchObject(['accountName' => 'User', 'password' => 'secret'])
        ->and(new ApiMe($account, true))->toMatchObject(['account' => $account, 'hasWorkspace' => true])
        ->and(PaginationMeta::fromValues(10, 2, 1))
        ->toMatchObject(['totalItems' => 10, 'totalPages' => 2, 'currentPage' => 1])
        ->and(WorkspaceDto::fromArray([
            'name' => 'Name',
            'owner' => 'Owner',
            'ownerUuid' => 'owner-uuid',
            'details' => null,
            'visibility' => Visibility::PUBLIC->value,
            'createdAt' => 'created',
            'updatedAt' => 'updated',
        ]))->toMatchObject(['name' => 'Name', 'description' => ''])
        ->and(WorkspaceList::fromArray(['one']))->toMatchObject(['workspaces' => ['one']])
        ->and(WorkspaceRequest::fromArray(['name' => 'Name', 'visibility' => (string) Visibility::PUBLIC->value]))
        ->toMatchObject(['name' => 'Name', 'description' => null, 'details' => null, 'visibility' => Visibility::PUBLIC->value])
        ->and(WorkspaceResponse::fromEntity($workspace, $account))
        ->toMatchObject([
            'ownerUuid' => $account->uuid->toString(),
            'name' => 'Test Workspace',
            'slug' => 'test-workspace',
        ]);
});

test('enum names and visibility rules are complete', function () {
    foreach (EventStatus::cases() as $status) {
        expect($status->getEventStatusName())->toBeString()->not->toBe('');
    }

    foreach (Visibility::cases() as $visibility) {
        expect($visibility->getVisibilityName())->toBeString()->not->toBe('');
    }
});

test('collections accept matching entities and expose collection operations', function () {
    $account = accountFixture();
    $auth = new AccountAccessAuth(1, 1, 'web', 'refresh', 'agent', 'hash', new DateTimeImmutable());
    $activation = new AccountActivation(1, $account->email, Uuid::uuid4(), new DateTimeImmutable());
    $token = new Token(1, 1, TokenType::EMail, Uuid::uuid4(), new DateTimeImmutable());
    $workspace = workspaceFixture();
    $event = new Event(
        1,
        Uuid::uuid4(),
        1,
        null,
        'Event',
        'event',
        null,
        null,
        EventStatus::DRAFT,
        Visibility::PUBLIC,
        new DateTimeImmutable(),
        new DateTimeImmutable('+1 hour'),
        new DateTimeImmutable(),
    );

    foreach ([
        [new AccountCollection(), $account],
        [new AccountAccessAuthCollection(), $auth],
        [new AccountActivationCollection(), $activation],
        [new TokenCollection(), $token],
        [new WorkspaceCollection(), $workspace],
        [new EventCollection(), $event],
    ] as [$collection, $entity]) {
        $collection[] = $entity;
        expect($collection)->toHaveCount(1)
            ->and($collection->first())->toBe($entity)
            ->and($collection->last())->toBe($entity)
            ->and($collection->getElements())->toHaveCount(1)
            ->and(json_encode($collection))->toBeString();

        expect(fn() => $collection[] = new \stdClass())->toThrow(InvalidArgumentException::class);
    }
});

test('collection iterator, filtering and missing offsets work', function () {
    $collection = new AccountCollection();
    $first = accountFixture();
    $second = accountFixture();
    $collection[] = $first;
    $collection[] = $second;

    expect($collection->offsetExists(0))->toBeTrue()
        ->and($collection[0])->toBe($first)
        ->and($collection->filter(static fn(Account $account): bool => $account === $second))
        ->toHaveCount(1);

    $collection->rewind();
    $seen = [];
    while ($collection->valid()) {
        $seen[] = $collection->current();
        $collection->next();
    }

    expect($seen)->toHaveCount(2)
        ->and($collection->key())->toBe(2);

    $collection->offsetUnset(0);
    expect($collection->offsetExists(0))->toBeFalse()
        ->and(fn() => $collection[99])->toThrow(\Core\SharedKernel\Domain\Exception\UndefinedOffsetException::class);
});

test('readonly domain entities can be cloned with changed values', function () {
    $account = accountFixture();

    expect($account->with(name: 'Changed'))
        ->toMatchObject(['name' => 'Changed', 'id' => 1])
        ->and($account->name)->toBe('Test User');
});
