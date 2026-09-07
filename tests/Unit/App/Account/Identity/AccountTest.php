<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountInterface;
use App\Mailing\Api\EmailType;
use Core\SharedKernel\Utils\Collectible;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function createAccount(): Account
{
    return new Account(
        null,
        Uuid::uuid4(),
        'Alice',
        'hashed-password',
        new EmailType('alice@example.com'),
        new DateTimeImmutable('2024-01-01 00:00:00'),
        null,
    );
}

test('constructor creates account with all properties', function (): void {
    $uuid = Uuid::uuid4();
    $now = new DateTimeImmutable();
    $account = new Account(42, $uuid, 'Bob', 'hash-bob', new EmailType('bob@example.com'), $now, $now);

    expect($account->id)->toBe(42)
        ->and($account->uuid)->toBe($uuid)
        ->and($account->name)->toBe('Bob')
        ->and($account->hashedPassword)->toBe('hash-bob')
        ->and($account->email)->toEqual(new EmailType('bob@example.com'))
        ->and($account->registeredAt)->toBe($now)
        ->and($account->lastActionAt)->toBe($now);
});

test('account implements account interface and collectible', function (): void {
    $account = createAccount();

    expect($account)->toBeInstanceOf(AccountInterface::class)
        ->and($account)->toBeInstanceOf(Collectible::class);
});

test('with id returns new instance with updated id', function (): void {
    $account = createAccount();
    $updated = $account->withId(99);

    expect($updated->id)->toBe(99)
        ->and($account->id)->toBeNull();
});

test('with name returns new instance with updated name', function (): void {
    $account = createAccount();
    $updated = $account->withName('Charlie');

    expect($updated->name)->toBe('Charlie')
        ->and($account->name)->toBe('Alice');
});

test('with password hash returns new instance with updated hash', function (): void {
    $account = createAccount();
    $updated = $account->withPasswordHash('new-hash');

    expect($updated->hashedPassword)->toBe('new-hash')
        ->and($account->hashedPassword)->toBe('hashed-password');
});

test('with email returns new instance with updated email', function (): void {
    $account = createAccount();
    $newEmail = new EmailType('charlie@example.com');
    $updated = $account->withEmail($newEmail);

    expect($updated->email)->toEqual($newEmail)
        ->and($account->email)->toEqual(new EmailType('alice@example.com'));
});

test('with registered at returns new instance with updated date', function (): void {
    $account = createAccount();
    $newDate = new DateTimeImmutable('2025-06-15');
    $updated = $account->withRegisteredAt($newDate);

    expect($updated->registeredAt)->toBe($newDate)
        ->and($account->registeredAt)->toEqual(new DateTimeImmutable('2024-01-01 00:00:00'));
});

test('refresh last action at sets timestamp without changing other properties', function (): void {
    $account = createAccount();
    $before = new DateTimeImmutable();
    $refreshed = $account->refreshLastActionAt();
    $after = new DateTimeImmutable();

    expect($refreshed->lastActionAt)->not->toBeNull()
        ->and($refreshed->lastActionAt->getTimestamp())->toBeGreaterThanOrEqual($before->getTimestamp())
        ->and($refreshed->lastActionAt->getTimestamp())->toBeLessThanOrEqual($after->getTimestamp())
        ->and($refreshed->name)->toBe('Alice')
        ->and($refreshed->email)->toEqual(new EmailType('alice@example.com'))
        ->and($account->lastActionAt)->toBeNull();
});

test('constructor rejects an invalid email address', function (): void {
    expect(fn (): EmailType => new EmailType('not-an-email'))
        ->toThrow(InvalidArgumentException::class);
});

test('construction with an invalid email yields no account', function (): void {
    expect(fn (): Account => new Account(
        null,
        Uuid::uuid4(),
        'Alice',
        'hash',
        new EmailType('not-an-email'),
        new DateTimeImmutable(),
        null,
    ))->toThrow(InvalidArgumentException::class);
});

test('with uuid passes an unsupported type and throws a type error', function (): void {
    $account = createAccount();
    $uuidFactory = $this->createMock(UuidFactoryInterface::class);

    expect(fn (): Account => $account->withUuid($uuidFactory))
        ->toThrow(\TypeError::class);
});

test('with methods preserve the untouched original instance', function (): void {
    $account = createAccount();
    $originalUuid = $account->uuid;
    $originalEmail = $account->email;

    $account->withId(1);
    $account->withName('Bob');
    $account->withPasswordHash('other');
    $account->withEmail(new EmailType('other@example.com'));
    $account->withRegisteredAt(new DateTimeImmutable('2030-01-01'));
    $account->refreshLastActionAt();

    expect($account->id)->toBeNull()
        ->and($account->name)->toBe('Alice')
        ->and($account->hashedPassword)->toBe('hashed-password')
        ->and($account->email)->toBe($originalEmail)
        ->and($account->registeredAt)->toEqual(new DateTimeImmutable('2024-01-01 00:00:00'))
        ->and($account->lastActionAt)->toBeNull()
        ->and($account->uuid)->toBe($originalUuid);
});
