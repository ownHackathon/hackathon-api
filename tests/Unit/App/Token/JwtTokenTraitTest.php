<?php declare(strict_types=1);

namespace Tests\Unit\App\Token;

use Core\SharedKernel\Trait\JwtTokenTrait;
use Firebase\JWT\JWT;
use InvalidArgumentException;

use function expect;
use function test;
use function time;

const TRAIT_TEST_SECRET = 'unit-test-secret-0123456789abcdef';

function traitConsumer(): object
{
    $config = new class ()
    {
        public readonly string $key;

        public readonly string $algorithmus;

        public function __construct()
        {
            $this->key = TRAIT_TEST_SECRET;
            $this->algorithmus = 'HS256';
        }
    };

    return new class ($config)
    {
        use JwtTokenTrait;

        public function __construct(
            public object $config,
        ) {
        }
    };
}

function encodeToken(array $payload, string $key = TRAIT_TEST_SECRET, string $algorithm = 'HS256'): string
{
    return JWT::encode($payload, $key, $algorithm);
}

test('trait validates a properly signed token', function (): void {
    $consumer = traitConsumer();
    $token = encodeToken(['iss' => 'issuer', 'exp' => time() + 300]);

    expect($consumer->isValid($token))->toBeTrue();
});

test('trait rejects a malformed token', function (): void {
    $consumer = traitConsumer();

    expect($consumer->isValid('not-a-jwt'))->toBeFalse();
});

test('trait rejects a token signed with a different key', function (): void {
    $consumer = traitConsumer();
    $token = encodeToken(['sub' => '1'], 'a-different-secret-0123456789abcdef');

    expect($consumer->isValid($token))->toBeFalse();
});

test('trait rejects an expired token', function (): void {
    $consumer = traitConsumer();
    $token = encodeToken(['exp' => time() - 5]);

    expect($consumer->isValid($token))->toBeFalse();
});

test('trait decodes a valid token into an object', function (): void {
    $consumer = traitConsumer();
    $token = encodeToken(['iss' => 'issuer', 'uuid' => '019becbe-f952-7b82-82fa-f41f8ae24599']);

    $decoded = $consumer->decode($token);

    expect($decoded)->toBeObject()
        ->and($decoded->iss)->toBe('issuer')
        ->and($decoded->uuid)->toBe('019becbe-f952-7b82-82fa-f41f8ae24599');
});

test('trait decode throws for an invalid token', function (): void {
    $consumer = traitConsumer();

    expect(fn (): object => $consumer->decode('not-a-jwt'))->toThrow(InvalidArgumentException::class);
});
