<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Token\Api\DTO\RawTokenDto;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;

use function expect;
use function test;

function createAccountAccessAuth(): AccountAccessAuth
{
    return new AccountAccessAuth(
        null,
        5,
        'web',
        new RawTokenDto('refresh-token'),
        'Mozilla/5.0',
        'client-hash',
        new DateTimeImmutable('2024-01-01 00:00:00'),
    );
}

test('constructor creates account access auth with all properties', function (): void {
    $now = new DateTimeImmutable();
    $token = new RawTokenDto('refresh-token');
    $auth = new AccountAccessAuth(9, 5, 'web', $token, 'Mozilla', 'hash', $now);

    expect($auth->id)->toBe(9)
        ->and($auth->accountId)->toBe(5)
        ->and($auth->label)->toBe('web')
        ->and($auth->refreshToken)->toBe($token)
        ->and($auth->userAgent)->toBe('Mozilla')
        ->and($auth->clientIdentHash)->toBe('hash')
        ->and($auth->createdAt)->toBe($now);
});

test('account access auth implements account access auth interface and collectible', function (): void {
    $auth = createAccountAccessAuth();

    expect($auth)->toBeInstanceOf(AccountAccessAuthInterface::class)
        ->and($auth)->toBeInstanceOf(Collectible::class);
});

test('with id returns new instance with updated id', function (): void {
    $auth = createAccountAccessAuth();
    $updated = $auth->withId(99);

    expect($updated->id)->toBe(99)
        ->and($auth->id)->toBeNull();
});

test('with account id returns new instance with updated account id', function (): void {
    $auth = createAccountAccessAuth();
    $updated = $auth->withAccountId(77);

    expect($updated->accountId)->toBe(77)
        ->and($auth->accountId)->toBe(5);
});

test('with label returns new instance with updated label', function (): void {
    $auth = createAccountAccessAuth();
    $updated = $auth->withLabel('mobile');

    expect($updated->label)->toBe('mobile')
        ->and($auth->label)->toBe('web');
});

test('with refresh token returns new instance with updated refresh token', function (): void {
    $auth = createAccountAccessAuth();
    $newToken = new RawTokenDto('new-refresh-token');
    $updated = $auth->withRefreshToken($newToken);

    expect($updated->refreshToken)->toBe($newToken)
        ->and($auth->refreshToken)->toEqual(new RawTokenDto('refresh-token'));
});

test('with client ident hash returns new instance with updated client ident hash', function (): void {
    $auth = createAccountAccessAuth();
    $updated = $auth->withClientIdentHash('new-hash');

    expect($updated->clientIdentHash)->toBe('new-hash')
        ->and($auth->clientIdentHash)->toBe('client-hash');
});

test('with methods preserve the untouched original instance', function (): void {
    $auth = createAccountAccessAuth();
    $originalToken = $auth->refreshToken;

    $auth->withId(1);
    $auth->withAccountId(2);
    $auth->withLabel('desktop');
    $auth->withRefreshToken(new RawTokenDto('other'));
    $auth->withClientIdentHash('other-hash');

    expect($auth->id)->toBeNull()
        ->and($auth->accountId)->toBe(5)
        ->and($auth->label)->toBe('web')
        ->and($auth->refreshToken)->toBe($originalToken)
        ->and($auth->clientIdentHash)->toBe('client-hash')
        ->and($auth->createdAt)->toEqual(new DateTimeImmutable('2024-01-01 00:00:00'));
});

test('with user agent returns new instance with updated user agent', function (): void {
    $auth = createAccountAccessAuth();
    $updated = $auth->withUserAgent('Other-Agent');

    expect($updated->userAgent)->toBe('Other-Agent')
        ->and($auth->userAgent)->toBe('Mozilla/5.0');
});
