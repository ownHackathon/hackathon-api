<?php declare(strict_types=1);

namespace Tests\Unit\App\Account;

use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\DTO\Client\ClientIdentificationData;
use App\Account\Identity\DTO\Token\JwtTokenConfig;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use Firebase\JWT\JWT;
use InvalidArgumentException;

use function expect;
use function test;

test('access token service generates and validates JWTs', function (): void {
    $config = new JwtTokenConfig('issuer', 'audience', 300, 'HS256', 'unit-test-secret');
    $service = new AccessTokenService($config);
    $uuid = \Ramsey\Uuid\Uuid::uuid4();
    $token = $service->generate($uuid);
    $decoded = $service->decode($token->accessToken);

    expect($service->isValid($token->accessToken))->toBeTrue()
        ->and($decoded->iss)->toBe('issuer')
        ->and($decoded->aud)->toBe('audience')
        ->and($decoded->uuid)->toBe($uuid->toString())
        ->and($service->isValid('invalid.jwt'))->toBeFalse()
        ->and(fn (): object => $service->decode('invalid.jwt'))->toThrow(InvalidArgumentException::class);
});

test('refresh token service generates a client-bound JWT', function (): void {
    $config = new JwtTokenConfig('issuer', 'audience', 300, 'HS256', 'unit-test-secret');
    $service = new RefreshTokenService(
        $this->createMock(AccountRepositoryInterface::class),
        $this->createMock(AccountAccessAuthRepositoryInterface::class),
        new AccessTokenService($config),
        $config,
    );
    $client = ClientIdentification::create(ClientIdentificationData::create('client', 'agent'), 'client-hash');
    $token = $service->generate($client);
    $decoded = JWT::decode($token->refreshToken, new \Firebase\JWT\Key($config->key, $config->algorithmus));

    expect($decoded->ident)->toBe('client-hash')->and($service->isValid($token->refreshToken))->toBeTrue();
});
