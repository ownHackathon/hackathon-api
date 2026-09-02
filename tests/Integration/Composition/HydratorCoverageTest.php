<?php declare(strict_types=1);

namespace Tests\Integration\Composition;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydrator;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydrator;
use ownHackathon\App\Token\Infrastructure\Hydrator\TokenHydrator;
use ownHackathon\App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\App\Policy\Domain\Enum\Visibility;
use ownHackathon\App\Event\Domain\Enum\EventStatus;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Clock\DateTimeFormat;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Log\LoggerInterface;

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
        'updatedAt' => '2024-01-02 10:00:00',
        'token' => '019becbe-f952-7b82-82fa-f41f8ae24599',
        'tokenType' => TokenType::EMail->value,
        'slug' => 'name',
        'description' => 'description',
        'details' => 'details',
        'visibility' => Visibility::PUBLIC->value,
        'workspaceId' => 3,
        'topicId' => 4,
        'status' => EventStatus::DRAFT->value,
        'beginsOn' => '2024-01-03 10:00:00',
        'endsOn' => '2024-01-04 10:00:00',
    ];
}

test('all entity hydrators hydrate, extract and handle collections', function () {
    $uuid = $this->container->get(UuidFactoryInterface::class);
    $logger = $this->container->get(LoggerInterface::class);
    $data = hydratorData();

    $accountHydrator = new AccountHydrator($uuid, $logger);
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

    $activationHydrator = new AccountActivationHydrator($uuid, $logger);
    $activation = $activationHydrator->hydrate($data);
    expect($activationHydrator->extract($activation))->toHaveSubset(['email' => $data['email']])
        ->and($activationHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($activationHydrator->extractCollection($activationHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $tokenHydrator = new TokenHydrator($uuid, $logger);
    $token = $tokenHydrator->hydrate($data);
    expect($tokenHydrator->extract($token))->toHaveSubset(['accountId' => 2, 'tokenType' => TokenType::EMail->value])
        ->and($tokenHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($tokenHydrator->extractCollection($tokenHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $workspaceHydrator = new WorkspaceHydrator($uuid, $logger);
    $workspace = $workspaceHydrator->hydrate($data);
    expect($workspaceHydrator->extract($workspace))->toHaveSubset(['name' => 'Name', 'visibility' => Visibility::PUBLIC->value])
        ->and($workspaceHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($workspaceHydrator->extractCollection($workspaceHydrator->hydrateCollection([$data])))->toHaveCount(1);

    $eventHydrator = new EventHydrator($uuid, $logger);
    $event = $eventHydrator->hydrate($data);
    expect($eventHydrator->extract($event))->toHaveSubset([
        'name' => 'Name',
        'status' => EventStatus::DRAFT->value,
        'visibility' => Visibility::PUBLIC->value,
    ])->and($eventHydrator->hydrateCollection([$data]))->toHaveCount(1)
        ->and($eventHydrator->extractCollection($eventHydrator->hydrateCollection([$data])))->toHaveCount(1);
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
    expect(fn() => new EmailType('invalid'))->toThrow(\ownHackathon\App\Mailing\Exception\InvalidArgumentException::class);
});
