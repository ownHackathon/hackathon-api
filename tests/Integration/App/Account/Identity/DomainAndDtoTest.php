<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use App\Account\Identity\Api\DTO\Account\AccountPassword;
use App\Account\Identity\Api\DTO\Account\AccountRegistration;
use App\Account\Identity\Api\DTO\Account\ApiMe;
use App\Account\Identity\Api\DTO\Token\AccountPasswordToken;
use App\Account\Identity\Domain\Account;
use App\Mailing\Api\EmailType;
use Core\SharedKernel\Domain\Exception\UndefinedOffsetException;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function accountFixture(): Account
{
    return new Account(
        id: 1,
        uuid: Uuid::uuid4(),
        name: 'Test User',
        hashedPassword: 'password',
        email: EmailType::fromString('test@example.com'),
        registeredAt: new DateTimeImmutable('2024-01-01'),
        lastActionAt: new DateTimeImmutable('2024-01-02'),
    );
}

test('DTO factories create their expected values', function () {
    $account = accountFixture();

    expect(AccountPassword::fromString('secret')->password)->toBe('secret')
        ->and(AccountPasswordToken::fromString('token')->accountPasswordToken)->toBe('token')
        ->and(AccountRegistration::fromString('User', 'secret'))
        ->toMatchObject(['accountName' => 'User', 'password' => 'secret'])
        ->and(new ApiMe($account, true))->toMatchObject(['account' => $account, 'hasWorkspace' => true]);
});

test('collection iterator, filtering and missing offsets work', function () {
    $collection = new \App\Account\Identity\Domain\AccountCollection();
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
        ->and(fn() => $collection[99])->toThrow(UndefinedOffsetException::class);
});

test('readonly domain entities can be cloned with changed values', function () {
    $account = accountFixture();

    expect($account->withName('Changed'))
        ->toMatchObject(['name' => 'Changed', 'id' => 1])
        ->and($account->name)->toBe('Test User');
});
