<?php declare(strict_types=1);

namespace Tests\Integration\Coverage;

use DateTimeImmutable;
use Mockery;
use ownHackathon\App\Account\Identity\Domain\Account;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuth;
use ownHackathon\App\Account\Identity\Domain\AccountActivation;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\App\Token\Domain\Token;
use ownHackathon\App\Token\Infrastructure\Hydrator\TokenHydratorInterface;
use ownHackathon\App\Token\Infrastructure\Persistence\Repository\TokenRepository;
use ownHackathon\App\Token\Infrastructure\Persistence\Table\TokenStoreInterface;
use ownHackathon\App\Workspace\Domain\Enum\Visibility;
use ownHackathon\App\Workspace\Domain\Workspace;
use ownHackathon\App\Workspace\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Core\Persistence\Pagination;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

test('repositories delegate persistence and queries to their store', function () {
    $account = new Account(1, Uuid::uuid4(), 'Account', 'hash', EmailType::fromString('repo@example.com'), new DateTimeImmutable(), null);
    $auth = new AccountAccessAuth(1, 1, 'web', 'refresh', 'agent', 'client', new DateTimeImmutable());
    $activation = new AccountActivation(1, $account->email, Uuid::uuid4(), new DateTimeImmutable());
    $token = new Token(1, 1, TokenType::EMail, Uuid::uuid4(), new DateTimeImmutable());
    $workspace = new Workspace(1, Uuid::uuid4(), 1, 'Workspace', 'workspace', null, null, Visibility::PUBLIC, new DateTimeImmutable(), new DateTimeImmutable());

    $accountStore = Mockery::mock(AccountStoreInterface::class);
    $accountStore->shouldReceive('persist')->andReturn(1);
    $accountStore->shouldReceive('update')->andReturn(true);
    $accountStore->shouldReceive('remove')->andReturn(true);
    $accountStore->shouldReceive('fetchOne')->andReturn(null);
    $accountStore->shouldReceive('fetchAll')->andReturn([]);
    $accountHydrator = Mockery::mock(AccountHydratorInterface::class);
    $accountHydrator->shouldReceive('extract')->andReturnUsing(static fn($entity): array => ['id' => $entity->id]);
    $accountHydrator->shouldReceive('hydrateCollection')->andReturn(new \ownHackathon\App\Account\Identity\Domain\AccountCollection());
    $accountRepo = new AccountRepository($accountStore, $accountHydrator);
    expect($accountRepo->insert($account))->toBe(1)
        ->and($accountRepo->update($account))->toBeTrue()
        ->and($accountRepo->deleteById(1))->toBeTrue()
        ->and($accountRepo->findOneById(1))->toBeNull()
        ->and($accountRepo->findOneByUuid($account->uuid))->toBeNull()
        ->and($accountRepo->findOneByName('Account'))->toBeNull()
        ->and($accountRepo->findOneByEmail($account->email))->toBeNull()
        ->and($accountRepo->findAll())->toHaveCount(0);

    $accessStore = Mockery::mock(AccountAccessAuthStoreInterface::class);
    $accessStore->shouldReceive('persist')->andReturn(1);
    $accessStore->shouldReceive('update')->andReturn(true);
    $accessStore->shouldReceive('remove')->andReturn(true);
    $accessStore->shouldReceive('fetchOne')->andReturn(null);
    $accessStore->shouldReceive('fetchMany')->andReturn([]);
    $accessStore->shouldReceive('fetchAll')->andReturn([]);
    $accessHydrator = Mockery::mock(AccountAccessAuthHydratorInterface::class);
    $accessHydrator->shouldReceive('extract')->andReturnUsing(static fn($entity): array => ['id' => $entity->id]);
    $accessHydrator->shouldReceive('hydrateCollection')->andReturn(new \ownHackathon\App\Account\Identity\Domain\AccountAccessAuthCollection());
    $accessRepo = new AccountAccessAuthRepository($accessStore, $accessHydrator);
    expect($accessRepo->insert($auth))->toBe(1)
        ->and($accessRepo->update($auth))->toBeTrue()
        ->and($accessRepo->deleteById(1))->toBeTrue()
        ->and($accessRepo->findOneById(1))->toBeNull()
        ->and($accessRepo->findByAccountId(1))->toHaveCount(0)
        ->and($accessRepo->findOneByAccountIdAndClientIdHash(1, 'client'))->toBeNull()
        ->and($accessRepo->findByLabel('web'))->toHaveCount(0)
        ->and($accessRepo->findOneByRefreshToken('refresh'))->toBeNull()
        ->and($accessRepo->findByUserAgent('agent'))->toHaveCount(0)
        ->and($accessRepo->findOneByClientIdentHash('client'))->toBeNull()
        ->and($accessRepo->findAll())->toHaveCount(0);

    $activationStore = Mockery::mock(AccountActivationStoreInterface::class);
    $activationStore->shouldReceive('persist')->andReturn(1);
    $activationStore->shouldReceive('update')->andReturn(true);
    $activationStore->shouldReceive('remove')->andReturn(true);
    $activationStore->shouldReceive('fetchOne')->andReturn(null);
    $activationStore->shouldReceive('fetchMany')->andReturn([]);
    $activationStore->shouldReceive('fetchAll')->andReturn([]);
    $activationHydrator = Mockery::mock(AccountActivationHydratorInterface::class);
    $activationHydrator->shouldReceive('extract')->andReturnUsing(static fn($entity): array => ['id' => $entity->id]);
    $activationHydrator->shouldReceive('hydrateCollection')->andReturn(new \ownHackathon\App\Account\Identity\Domain\AccountActivationCollection());
    $activationRepo = new AccountActivationRepository($activationStore, $activationHydrator);
    expect($activationRepo->insert($activation))->toBe(1)
        ->and($activationRepo->update($activation))->toBeTrue()
        ->and($activationRepo->findOneById(1))->toBeNull()
        ->and($activationRepo->findByEmail($account->email))->toHaveCount(0)
        ->and($activationRepo->findOneByToken('token'))->toBeNull()
        ->and($activationRepo->findAll())->toHaveCount(0)
        ->and($activationRepo->deleteById(1))->toBeTrue()
        ->and($activationRepo->deleteByEmail($account->email))->toBeTrue();

    $tokenStore = Mockery::mock(TokenStoreInterface::class);
    $tokenStore->shouldReceive('persist')->andReturn(1);
    $tokenStore->shouldReceive('update')->andReturn(true);
    $tokenStore->shouldReceive('remove')->andReturn(true);
    $tokenStore->shouldReceive('fetchOne')->andReturn(null);
    $tokenStore->shouldReceive('fetchMany')->andReturn([]);
    $tokenStore->shouldReceive('fetchAll')->andReturn([]);
    $tokenHydrator = Mockery::mock(TokenHydratorInterface::class);
    $tokenHydrator->shouldReceive('extract')->andReturnUsing(static fn($entity): array => ['id' => $entity->id]);
    $tokenHydrator->shouldReceive('hydrateCollection')->andReturn(new \ownHackathon\App\Token\Domain\TokenCollection());
    $tokenRepo = new TokenRepository($tokenStore, $tokenHydrator);
    expect($tokenRepo->insert($token))->toBe(1)
        ->and($tokenRepo->update($token))->toBeTrue()
        ->and($tokenRepo->findOneById(1))->toBeNull()
        ->and($tokenRepo->findByAccountId(1))->toHaveCount(0)
        ->and($tokenRepo->findOneByToken('token'))->toBeNull()
        ->and($tokenRepo->findAll())->toHaveCount(0)
        ->and($tokenRepo->deleteById(1))->toBeTrue()
        ->and($tokenRepo->deleteByAccountId(1))->toBeTrue();

    $workspaceStore = Mockery::mock(WorkspaceStoreInterface::class);
    $workspaceStore->shouldReceive('persist')->andReturn(1);
    $workspaceStore->shouldReceive('update')->andReturn(true);
    $workspaceStore->shouldReceive('remove')->andReturn(true);
    $workspaceStore->shouldReceive('fetchOne')->andReturn(null);
    $workspaceStore->shouldReceive('fetchMany')->andReturn([]);
    $workspaceStore->shouldReceive('fetchAll')->andReturn([]);
    $workspaceStore->shouldReceive('count')->andReturn(0);
    $workspaceHydrator = Mockery::mock(WorkspaceHydratorInterface::class);
    $workspaceHydrator->shouldReceive('extract')->andReturnUsing(static fn($entity): array => ['id' => $entity->id]);
    $workspaceHydrator->shouldReceive('hydrateCollection')->andReturn(new \ownHackathon\App\Workspace\Domain\WorkspaceCollection());
    $workspaceRepo = new WorkspaceRepository($workspaceStore, $workspaceHydrator);
    expect($workspaceRepo->insert($workspace))->toBe(1)
        ->and($workspaceRepo->update($workspace))->toBeTrue()
        ->and($workspaceRepo->deleteById(1))->toBeTrue()
        ->and($workspaceRepo->findOneById(1))->toBeNull()
        ->and($workspaceRepo->findByAccountId(1, new Pagination(1, 5, 0)))->toHaveCount(0)
        ->and($workspaceRepo->findOneByName('Workspace'))->toBeNull()
        ->and($workspaceRepo->findOneBySlug('workspace'))->toBeNull()
        ->and($workspaceRepo->findAll())->toHaveCount(0)
        ->and($workspaceRepo->countByAccount(1))->toBe(0);
});
