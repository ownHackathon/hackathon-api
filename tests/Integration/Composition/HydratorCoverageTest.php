<?php declare(strict_types=1);

namespace Tests\Integration\Composition;

use App\Account\Identity\Api\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator;
use App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydrator;
use App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use App\Mailing\Api\EmailType;
use App\Token\Api\Enum\TokenType;
use App\Token\Api\TokenLoggerInterface;
use App\Token\Infrastructure\Hydrator\TokenHydrator;
use Core\SharedKernel\Utils\UuidFactoryInterface;

use function expect;
use function test;

function hydratorData(): array
{
    return [
        'id' => 1,
        'uuid' => '019becbe-f952-7b82-82fa-f41f8ae24599',
        'accountId' => 2,
        'name' => 'Name',
        'email' => 'hydrate@example.com',
        'password' => 'hash',
        'registeredAt' => '2024-01-01 10:00:00',
        'lastActionAt' => '2024-01-02 10:00:00',
        'label' => 'web',
        'refreshToken' => 'refresh',
        'userAgent' => 'agent',
        'clientIdentHash' => 'client',
        'createdAt' => '2024-01-01 10:00:00',
        'token' => '019becbe-f952-7b82-82fa-f41f8ae24599',
        'tokenType' => TokenType::EMail->value,
    ];
}

test('all entity hydrators hydrate, extract and handle collections', function () {
    $uuid = $this->container->get(UuidFactoryInterface::class);
    $identityLogger = $this->container->get(IdentityLoggerInterface::class);
    $tokenLogger = $this->container->get(TokenLoggerInterface::class);
    $data = hydratorData();

    $accountHydrator = new AccountHydrator($uuid, $identityLogger);
    $account = $accountHydrator->hydrate($data);
    expect($accountHydrator->extract($account))->toHaveSubset([
        'id' => 1,
        'uuid' => $data['uuid'],
        'email' => $data['email'],
    ])->and($accountHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($accountHydrator->extractCollection($accountHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $accountData = $data;
    $accountData['lastActionAt'] = null;
    expect($accountHydrator->extract($accountHydrator->hydrate($accountData))['lastActionAt'])->toBeNull();

    $accessHydrator = new AccountAccessAuthHydrator();
    $access = $accessHydrator->hydrate($data);
    expect($accessHydrator->extract($access))->toHaveSubset(['label' => 'web', 'refreshToken' => 'refresh'])
        ->and($accessHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($accessHydrator->extractCollection($accessHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $activationHydrator = new AccountActivationHydrator($uuid, $identityLogger);
    $activation = $activationHydrator->hydrate($data);
    expect($activationHydrator->extract($activation))->toHaveSubset(['email' => $data['email']])
        ->and($activationHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($activationHydrator->extractCollection($activationHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $tokenHydrator = new TokenHydrator($uuid, $tokenLogger);
    $token = $tokenHydrator->hydrate($data);
    expect($tokenHydrator->extract($token))->toHaveSubset(['accountId' => 2, 'tokenType' => TokenType::EMail->value])
        ->and($tokenHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($tokenHydrator->extractCollection($tokenHydrator->hydrateCollection([$data])))->toHaveCount(1);
});

test('email value object supports all serialization forms and rejects invalid values', function () {
    $email = new EmailType('serialize@example.com');

    expect($email->toString())->toBe('serialize@example.com')
        ->and((string) $email)->toBe('serialize@example.com')
        ->and($email->serialize())->toBe('serialize@example.com')
        ->and($email->__serialize())->toBe(['string' => 'serialize@example.com'])
        ->and($email->jsonSerialize())->toBe('serialize@example.com')
        ->and(EmailType::fromString('factory@example.com')->toString())->toBe('factory@example.com')
        ->and(new EmailType($email)->toString())->toBe('serialize@example.com');

    $restored = new EmailType('old@example.com');
    $restored->unserialize('new@example.com');
    expect($restored->toString())->toBe('new@example.com');
    expect(fn() => new EmailType('invalid'))->toThrow(\App\Mailing\Exception\InvalidArgumentException::class);
});
