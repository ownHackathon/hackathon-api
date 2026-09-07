<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\AccountActivationInterface;
use App\Mailing\Api\EmailType;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function expect;
use function test;

function createAccountActivation(): AccountActivation
{
    return new AccountActivation(
        null,
        new EmailType('alice@example.com'),
        Uuid::uuid4(),
        new DateTimeImmutable('2024-01-01 00:00:00'),
    );
}

test('constructor creates account activation with all properties', function (): void {
    $token = Uuid::uuid4();
    $now = new DateTimeImmutable();
    $activation = new AccountActivation(3, new EmailType('bob@example.com'), $token, $now);

    expect($activation->id)->toBe(3)
        ->and($activation->email)->toEqual(new EmailType('bob@example.com'))
        ->and($activation->token)->toBe($token)
        ->and($activation->createdAt)->toBe($now);
});

test('account activation implements account activation interface and collectible', function (): void {
    $activation = createAccountActivation();

    expect($activation)->toBeInstanceOf(AccountActivationInterface::class)
        ->and($activation)->toBeInstanceOf(Collectible::class);
});

test('with id returns new instance with updated id', function (): void {
    $activation = createAccountActivation();
    $updated = $activation->withId(99);

    expect($updated->id)->toBe(99)
        ->and($activation->id)->toBeNull();
});

test('with email returns new instance with updated email', function (): void {
    $activation = createAccountActivation();
    $newEmail = new EmailType('charlie@example.com');
    $updated = $activation->withEmail($newEmail);

    expect($updated->email)->toEqual($newEmail)
        ->and($activation->email)->toEqual(new EmailType('alice@example.com'));
});

test('with token returns new instance with updated token', function (): void {
    $activation = createAccountActivation();
    $newToken = Uuid::uuid4();
    $updated = $activation->withToken($newToken);

    expect($updated->token)->toBe($newToken)
        ->and($activation->token)->not->toBe($newToken);
});

test('with created at returns new instance with updated created at', function (): void {
    $activation = createAccountActivation();
    $newDate = new DateTimeImmutable('2025-06-15');
    $updated = $activation->withCreatedAt($newDate);

    expect($updated->createdAt)->toBe($newDate)
        ->and($activation->createdAt)->toEqual(new DateTimeImmutable('2024-01-01 00:00:00'));
});

test('with methods preserve the untouched original instance', function (): void {
    $activation = createAccountActivation();
    $originalToken = $activation->token;
    $originalEmail = $activation->email;

    $activation->withId(1);
    $activation->withEmail(new EmailType('other@example.com'));
    $activation->withToken(Uuid::uuid4());
    $activation->withCreatedAt(new DateTimeImmutable('2030-01-01'));

    expect($activation->id)->toBeNull()
        ->and($activation->email)->toBe($originalEmail)
        ->and($activation->token)->toBe($originalToken)
        ->and($activation->createdAt)->toEqual(new DateTimeImmutable('2024-01-01 00:00:00'));
});

test('constructor rejects an invalid email address', function (): void {
    expect(fn (): EmailType => new EmailType('not-an-email'))
        ->toThrow(InvalidArgumentException::class);
});

test('construction with an invalid email yields no account activation', function (): void {
    expect(fn (): AccountActivation => new AccountActivation(
        null,
        new EmailType('not-an-email'),
        Uuid::uuid4(),
        new DateTimeImmutable(),
    ))->toThrow(InvalidArgumentException::class);
});

test('activation token is a uuid value object', function (): void {
    $activation = createAccountActivation();

    expect($activation->token)->toBeInstanceOf(UuidInterface::class);
});
