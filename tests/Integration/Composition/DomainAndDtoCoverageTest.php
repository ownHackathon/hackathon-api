<?php declare(strict_types=1);

namespace Tests\Integration\Composition;

use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthCollection;
use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\AccountActivationCollection;
use App\Account\Identity\Domain\AccountCollection;
use App\Account\Identity\DTO\Account\AccountPassword;
use App\Account\Identity\DTO\Account\AccountRegistration;
use App\Account\Identity\DTO\Account\ApiMe;
use App\Account\Identity\DTO\Token\AccountPasswordToken;
use App\Mailing\Api\EmailType;
use App\Token\Api\DTO\RawTokenDto;
use App\Token\Api\Enum\TokenType;
use App\Token\Domain\Entity\Token;
use App\Token\Domain\Entity\TokenCollection;
use DateTimeImmutable;
use InvalidArgumentException;
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

test('collections accept matching entities and expose collection operations', function () {
    $account = accountFixture();
    $auth = new AccountAccessAuth(1, 1, 'web', RawTokenDto::fromString('refresh'), 'agent', 'hash', new DateTimeImmutable());
    $activation = new AccountActivation(1, $account->email, Uuid::uuid4(), new DateTimeImmutable());
    $token = new Token(1, 1, TokenType::EMail, Uuid::uuid4(), new DateTimeImmutable());

    foreach ([
        [new AccountCollection(), $account],
        [new AccountAccessAuthCollection(), $auth],
        [new AccountActivationCollection(), $activation],
        [new TokenCollection(), $token],
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

    expect($account->withName('Changed'))
        ->toMatchObject(['name' => 'Changed', 'id' => 1])
        ->and($account->name)->toBe('Test User');
});
