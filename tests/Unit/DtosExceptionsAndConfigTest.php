<?php declare(strict_types=1);

namespace Tests\Unit;

use ownHackathon\App\Account\ConfigProvider as AccountConfigProvider;
use ownHackathon\App\Account\Identity\ConfigProvider as IdentityConfigProvider;
use ownHackathon\App\Account\Identity\DTO\Account\AccountPassword;
use ownHackathon\App\Account\Identity\DTO\Account\AuthenticationRequest;
use ownHackathon\App\Account\Identity\DTO\Response\AuthenticationResponse;
use ownHackathon\App\Account\Identity\DTO\Token\AccessToken;
use ownHackathon\App\Account\Identity\DTO\Token\AccountPasswordToken;
use ownHackathon\App\Account\Identity\DTO\Token\JwtTokenConfig;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use ownHackathon\App\Event\ConfigProvider as EventConfigProvider;
use ownHackathon\App\Http\ConfigProvider as HttpConfigProvider;
use ownHackathon\App\Http\DTO\HttpResponseMessage;
use ownHackathon\App\Mailing\ConfigProvider as MailingConfigProvider;
use ownHackathon\App\Mailing\DTO\EMail;
use ownHackathon\App\Token\ConfigProvider as TokenConfigProvider;
use ownHackathon\App\Token\DTO\Token;
use ownHackathon\App\Workspace\ConfigProvider as WorkspaceConfigProvider;
use ownHackathon\App\Workspace\DTO\Workspace;
use ownHackathon\App\Workspace\DTO\WorkspaceList;
use ownHackathon\Core\ConfigProvider as CoreConfigProvider;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;

use function expect;
use function test;

test('token, account, mailing and HTTP DTO factories map all values', function (): void {
    expect(AccountPassword::fromString('secret')->password)->toBe('secret')
        ->and(AccountPasswordToken::fromString('token')->accountPasswordToken)->toBe('token')
        ->and(AccessToken::fromString('access')->accessToken)->toBe('access')
        ->and(RefreshToken::fromString('refresh')->refreshToken)->toBe('refresh')
        ->and(AuthenticationRequest::fromArray([]))->toEqual(new AuthenticationRequest('', ''))
        ->and(EMail::fromString('a@example.org')->email)->toBe('a@example.org')
        ->and(Token::fromString(null)->token)->toBeNull()
        ->and(HttpResponseMessage::create(201, 'created'))->toEqual(new HttpResponseMessage(201, 'created'));

    $config = JwtTokenConfig::createFromArray([
        'iss' => 'issuer', 'aud' => 'audience', 'duration' => '60', 'algorithmus' => 'HS256', 'key' => 'secret',
    ]);
    expect($config->duration)->toBe(60);
    expect(Workspace::fromArray([
        'name' => 'Team', 'owner' => 'Alice', 'ownerUuid' => 'owner', 'details' => null,
        'visibility' => \ownHackathon\Core\SharedKernel\Domain\Enum\Visibility::PUBLIC->value, 'createdAt' => 'created', 'updatedAt' => 'updated',
    ])->description)->toBe('');
    expect(WorkspaceList::fromArray(['one'])->workspaces)->toBe(['one']);
    expect(AuthenticationResponse::from(new AccessToken('a'), new RefreshToken('r')))
        ->toEqual(new AuthenticationResponse('a', 'r'));
});

test('domain exceptions retain their diagnostic values', function (): void {
    $exception = new \ownHackathon\App\Account\Identity\Domain\Exception\SecurityBreachException('expected', 'actual', 'browser', 'other');
    expect($exception->expectedClientHash)->toBe('expected')
        ->and($exception->actualUserAgent)->toBe('other');

    $duplicate = new DuplicateEntryException('Account', ['email' => 'a@example.org']);
    expect($duplicate->getCode())->toBe(400)->and($duplicate->getMessage())->toContain('Account');
});

test('all module config providers expose their public configuration methods', function (): void {
    $providers = [
        new AccountConfigProvider(), new IdentityConfigProvider(), new EventConfigProvider(),
        new HttpConfigProvider(), new MailingConfigProvider(), new TokenConfigProvider(),
        new WorkspaceConfigProvider(), new CoreConfigProvider(), new \ownHackathon\ConfigProvider(),
    ];
    foreach ($providers as $provider) {
        expect($provider())->toBeArray();
    }
    expect((new IdentityConfigProvider())->getRoutes())->not->toBeEmpty()
        ->and((new IdentityConfigProvider())->getDependencies())->toHaveKey('factories')
        ->and((new WorkspaceConfigProvider())->getRoutes())->not->toBeEmpty()
        ->and((new WorkspaceConfigProvider())->getAbstractFactoryConfig())->toBeArray()
        ->and((new MailingConfigProvider())->getDependencies())->toHaveKey('aliases');
});
