<?php declare(strict_types=1);

namespace Tests\Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use ownHackathon\App\Account\Identity\Domain\Account;
use ownHackathon\App\Account\Identity\Domain\AccountCollection;
use ownHackathon\App\Account\Identity\Domain\Enum\AccountRoles;
use ownHackathon\App\Account\Identity\Domain\Enum\AccountVisibleStatus;
use ownHackathon\App\Event\Domain\Enum\EventStatus;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\Core\SharedKernel\Domain\Enum\Visibility;
use ownHackathon\Core\Persistence\Pagination;
use ownHackathon\Core\SharedKernel\Domain\Exception\UndefinedOffsetException;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

test('pagination normalizes bounds and calculates offset', function (): void {
    $pagination = Pagination::fromParams([]);
    expect([$pagination->page, $pagination->limit, $pagination->offset])->toBe([1, 5, 0])
        ->and(Pagination::fromParams(['page' => 3, 'limit' => 10])->offset)->toBe(20)
        ->and(Pagination::fromParams(['limit' => 999])->limit)->toBe(250)
        ->and(Pagination::fromParams(['page' => 0, 'limit' => 0])->page)->toBe(1)
        ->and(Pagination::fromParams(['page' => 2, 'limit' => -5])->limit)->toBe(1);
});

test('collection provides typed collection operations', function (): void {
    $account = new Account(1, Uuid::uuid4(), 'Alice', 'hash', new EmailType('alice@example.com'), new DateTimeImmutable(), null);
    $collection = new AccountCollection();
    $collection[] = $account;
    $collection[3] = $account;

    expect($collection)->toHaveCount(2)
        ->and($collection->first())->toBe($account)
        ->and($collection->last())->toBe($account)
        ->and($collection->filter(static fn (Account $item): bool => $item->name === 'Alice'))->toBe([0 => $account, 3 => $account])
        ->and($collection->jsonSerialize())->toBe([0 => $account, 3 => $account]);
    expect(fn (): mixed => $collection[] = new \stdClass())->toThrow(InvalidArgumentException::class);
});

test('collection throws for unknown offset', function (): void {
    expect(fn (): mixed => (new AccountCollection())[42])->toThrow(UndefinedOffsetException::class);
});

test('email type supports factories and serialization', function (): void {
    $email = EmailType::fromString('person@example.org');
    expect((string) $email)->toBe('person@example.org')
        ->and($email->__serialize())->toBe(['string' => 'person@example.org'])
        ->and($email->jsonSerialize())->toBe('person@example.org')
        ->and(fn (): EmailType => new EmailType('not-an-email'))
        ->toThrow(\ownHackathon\App\Mailing\Exception\InvalidArgumentException::class);
});

test('enums expose names and visibility rules', function (): void {
    expect(AccountRoles::Owner->getAccountRoleName())->toBe('Eigentümer')
        ->and(AccountVisibleStatus::DO_NOT_DISTURB->getVisibleStatusName())->toBe('Bitte nicht stören')
        ->and(EventStatus::RUNNING->getEventStatusName())->toBe('Running')
        ->and(Visibility::PUBLIC->isVisibleTo(null, false))->toBeTrue()
        ->and(Visibility::REGISTERED->isVisibleTo(null, false))->toBeFalse()
        ->and(Visibility::UNLISTED->isVisibleTo(null, true))->toBeTrue()
        ->and(Visibility::UNLISTED->isVisibleTo(null, false))->toBeFalse()
        ->and(TokenType::EMail->value)->toBe(2);
});

test('all enum cases expose a non-empty display name', function (): void {
    foreach (AccountRoles::cases() as $role) {
        expect($role->getAccountRoleName())->not->toBe('');
    }
    foreach (AccountVisibleStatus::cases() as $status) {
        expect($status->getVisibleStatusName())->not->toBe('');
    }
    foreach (EventStatus::cases() as $status) {
        expect($status->getEventStatusName())->not->toBe('');
    }
    foreach (Visibility::cases() as $visibility) {
        expect($visibility->getVisibilityName())->not->toBe('');
    }
});

test('readonly entities can be copied with changed properties', function (): void {
    $account = new Account(1, Uuid::uuid4(), 'Alice', 'hash', new EmailType('alice@example.com'), new DateTimeImmutable(), null);
    $changed = $account->with(name: 'Bob');
    expect($changed->name)->toBe('Bob')->and($account->name)->toBe('Alice');
});
