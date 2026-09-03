<?php declare(strict_types=1);

namespace Tests\Unit\App\Account;

use DateTimeImmutable;
use InvalidArgumentException;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountCollection;
use App\Mailing\Domain\EmailType;
use Core\SharedKernel\Domain\Exception\UndefinedOffsetException;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

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

test('readonly entities can be copied with changed properties', function (): void {
    $account = new Account(1, Uuid::uuid4(), 'Alice', 'hash', new EmailType('alice@example.com'), new DateTimeImmutable(), null);
    $changed = $account->with(name: 'Bob');
    expect($changed->name)->toBe('Bob')->and($account->name)->toBe('Alice');
});