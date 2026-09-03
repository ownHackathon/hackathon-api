<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\ConfigProvider as AccountConfigProvider;
use App\Account\Identity\ConfigProvider as IdentityConfigProvider;
use App\Account\Identity\DTO\Account\AccountPassword;
use App\Account\Identity\DTO\Account\AuthenticationRequest;
use App\Account\Identity\DTO\Response\AuthenticationResponse;
use App\Account\Identity\DTO\Token\AccessToken;
use App\Account\Identity\DTO\Token\AccountPasswordToken;
use App\Account\Identity\DTO\Token\JwtTokenConfig;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Event\ConfigProvider as EventConfigProvider;
use Core\Http\ConfigProvider as HttpConfigProvider;
use Core\Http\DTO\HttpResponseMessage;
use App\Mailing\ConfigProvider as MailingConfigProvider;
use App\Mailing\DTO\EMail;
use App\Token\ConfigProvider as TokenConfigProvider;
use App\Token\DTO\Token;
use App\Workspace\ConfigProvider as WorkspaceConfigProvider;
use App\Workspace\DTO\Workspace;
use App\Workspace\DTO\WorkspaceList;
use Core\ConfigProvider as CoreConfigProvider;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use HackathonApi\ConfigProvider as RootConfigProvider;

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
        'visibility' => \App\Policy\Domain\Enum\Visibility::PUBLIC->value, 'createdAt' => 'created', 'updatedAt' => 'updated',
    ])->description)->toBe('');
    expect(WorkspaceList::fromArray(['one'])->workspaces)->toBe(['one']);
    expect(AuthenticationResponse::from(new AccessToken('a'), new RefreshToken('r')))
        ->toEqual(new AuthenticationResponse('a', 'r'));
});

test('domain exceptions retain their diagnostic values', function (): void {
    $exception = new \App\Account\Identity\Domain\Exception\SecurityBreachException('expected', 'actual', 'browser', 'other');
    expect($exception->expectedClientHash)->toBe('expected')
        ->and($exception->actualUserAgent)->toBe('other');

    $duplicate = new DuplicateEntryException('Account', ['email' => 'a@example.org']);
    expect($duplicate->getCode())->toBe(400)->and($duplicate->getMessage())->toContain('Account');
});

test('all module config providers expose their public configuration methods', function (): void {
    $providers = [
        new AccountConfigProvider(), new IdentityConfigProvider(), new EventConfigProvider(),
        new HttpConfigProvider(), new MailingConfigProvider(), new TokenConfigProvider(),
        new WorkspaceConfigProvider(), new CoreConfigProvider(), new RootConfigProvider(),
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
