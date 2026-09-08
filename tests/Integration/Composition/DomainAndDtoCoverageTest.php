<?php declare(strict_types=1);

namespace Tests\Integration\Composition;

use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthCollection;
use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\AccountActivationCollection;
use App\Account\Identity\Domain\AccountCollection;
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
