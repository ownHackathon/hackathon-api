<?php declare(strict_types=1);

namespace Tests\Unit\App\Token;

use App\Token\Api\DTO\PasswordChangeTokenDto;
use App\Token\Api\Enum\TokenType;
use App\Token\Domain\Entity\Token;
use App\Token\Domain\Entity\TokenCollection;
use App\Token\Domain\Entity\TokenInterface;
use Core\SharedKernel\Utils\Collectible;
use Core\SharedKernel\Utils\UuidFactory;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function tokenFixture(): Token
{
    return new Token(
        id: 1,
        accountId: 2,
        tokenType: TokenType::EMail,
        token: Uuid::fromString('019becbe-f952-7b82-82fa-f41f8ae24599'),
        createdAt: new DateTimeImmutable('2024-01-01 10:00:00'),
    );
}

test('token constructor sets all properties', function (): void {
    $token = tokenFixture();

    expect($token->id)->toBe(1)
        ->and($token->accountId)->toBe(2)
        ->and($token->tokenType)->toBe(TokenType::EMail)
        ->and($token->token->toString())->toBe('019becbe-f952-7b82-82fa-f41f8ae24599')
        ->and($token->createdAt->format('Y-m-d H:i:s'))->toBe('2024-01-01 10:00:00');
});

test('token implements the token interface and is collectible', function (): void {
    $token = tokenFixture();

    expect($token)->toBeInstanceOf(TokenInterface::class)
        ->and($token)->toBeInstanceOf(Collectible::class);
});

test('with id returns a new instance with the updated id', function (): void {
    $token = tokenFixture();

    $changed = $token->withId(99);

    expect($changed)->toBeInstanceOf(TokenInterface::class)
        ->and($changed->id)->toBe(99)
        ->and($changed->accountId)->toBe($token->accountId)
        ->and($token->id)->toBe(1);
});

test('with account id returns a new instance with the updated account id', function (): void {
    $token = tokenFixture();

    $changed = $token->withAccountId(123);

    expect($changed->accountId)->toBe(123)
        ->and($token->accountId)->toBe(2);
});

test('with token type returns a new instance with the updated token type', function (): void {
    $token = tokenFixture();

    $changed = $token->withTokenType(TokenType::Default);

    expect($changed->tokenType)->toBe(TokenType::Default)
        ->and($token->tokenType)->toBe(TokenType::EMail);
});

test('with token type rejects an unsupported token type', function (): void {
    $token = tokenFixture();

    expect(fn (): TokenInterface => $token->withTokenType('invalid'))->toThrow(\TypeError::class);
});

test('with token passes an unsupported type and throws a type error', function (): void {
    $token = tokenFixture();

    expect(fn (): TokenInterface => $token->withToken(new UuidFactory()))->toThrow(\TypeError::class);
});

test('with created at returns a new instance with the updated created at', function (): void {
    $token = tokenFixture();

    $changed = $token->withCreatedAt(new DateTimeImmutable('2025-02-03 04:05:06'));

    expect($changed->createdAt->format('Y-m-d H:i:s'))->toBe('2025-02-03 04:05:06')
        ->and($token->createdAt->format('Y-m-d H:i:s'))->toBe('2024-01-01 10:00:00');
});

test('with methods preserve the untouched original instance', function (): void {
    $token = tokenFixture();

    $token->withId(99);
    $token->withAccountId(123);
    $token->withTokenType(TokenType::Default);
    $token->withCreatedAt(new DateTimeImmutable('2025-02-03 04:05:06'));

    expect($token->id)->toBe(1)
        ->and($token->accountId)->toBe(2)
        ->and($token->tokenType)->toBe(TokenType::EMail)
        ->and($token->createdAt->format('Y-m-d H:i:s'))->toBe('2024-01-01 10:00:00');
});

test('token constructor rejects a non uuid token', function (): void {
    expect(fn (): Token => new Token(
        1,
        2,
        TokenType::EMail,
        'not-a-uuid',
        new DateTimeImmutable(),
    ))->toThrow(\TypeError::class);
});

test('token constructor rejects an invalid account id', function (): void {
    expect(fn (): Token => new Token(
        1,
        '2',
        TokenType::EMail,
        Uuid::uuid4(),
        new DateTimeImmutable(),
    ))->toThrow(\TypeError::class);
});

test('token collection accepts tokens and offers collection operations', function (): void {
    $collection = new TokenCollection();
    $collection[] = tokenFixture();

    expect($collection)->toHaveCount(1)
        ->and($collection->first())->toBeInstanceOf(TokenInterface::class)
        ->and($collection->last())->toBeInstanceOf(TokenInterface::class)
        ->and($collection->getElements())->toHaveCount(1)
        ->and(json_encode($collection))->toBeString();
});

test('token collection rejects an invalid element', function (): void {
    $collection = new TokenCollection();

    expect(fn () => $collection[] = new \stdClass())
        ->toThrow(\InvalidArgumentException::class);
});

test('token collection rejects a null element', function (): void {
    $collection = new TokenCollection();

    expect(fn () => $collection[] = null)
        ->toThrow(\InvalidArgumentException::class);
});

test('password change token dto stores all values', function (): void {
    $token = Uuid::uuid4();
    $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');

    $dto = new PasswordChangeTokenDto(1, 2, TokenType::EMail, $token, $createdAt);

    expect($dto->id)->toBe(1)
        ->and($dto->accountId)->toBe(2)
        ->and($dto->tokenType)->toBe(TokenType::EMail)
        ->and($dto->token)->toBe($token)
        ->and($dto->createdAt)->toBe($createdAt);
});

test('password change token dto rejects a non uuid token', function (): void {
    expect(fn (): PasswordChangeTokenDto => new PasswordChangeTokenDto(
        1,
        2,
        TokenType::EMail,
        'not-a-uuid',
        new DateTimeImmutable(),
    ))->toThrow(\TypeError::class);
});
