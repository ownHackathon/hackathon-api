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
use App\Mailing\Api\DTO\EMailDto;
use App\Mailing\ConfigProvider as MailingConfigProvider;
use App\Token\Api\DTO\RawTokenDto;
use App\Token\ConfigProvider as TokenConfigProvider;
use Core\ConfigProvider as CoreConfigProvider;
use Core\Http\ConfigProvider as HttpConfigProvider;
use Core\Http\DTO\HttpResponseMessage;
use HackathonApi\ConfigProvider as RootConfigProvider;

use function expect;
use function test;

test('token, account, mailing and HTTP DTO factories map all values', function (): void {
    expect(AccountPassword::fromString('secret')->password)->toBe('secret')
        ->and(AccountPasswordToken::fromString('token')->accountPasswordToken)->toBe('token')
        ->and(AccessToken::fromString('access')->accessToken)->toBe('access')
        ->and(RefreshToken::fromString('refresh')->refreshToken)->toBe('refresh')
        ->and(AuthenticationRequest::fromArray([]))->toEqual(new AuthenticationRequest('', ''))
        ->and(EMailDto::fromString('a@example.org')->email)->toBe('a@example.org')
        ->and(RawTokenDto::fromString(null)->token)->toBeNull()
        ->and(HttpResponseMessage::create(201, 'created'))->toEqual(new HttpResponseMessage(201, 'created'));

    $config = JwtTokenConfig::createFromArray([
        'iss' => 'issuer', 'aud' => 'audience', 'duration' => '60', 'algorithmus' => 'HS256', 'key' => 'secret',
    ]);
    expect($config->duration)->toBe(60);
    expect(AuthenticationResponse::from(new AccessToken('a'), new RefreshToken('r')))
        ->toEqual(new AuthenticationResponse('a', 'r'));
});

test('all module config providers expose their public configuration methods', function (): void {
    $providers = [
        new AccountConfigProvider(), new IdentityConfigProvider(),
        new HttpConfigProvider(), new MailingConfigProvider(), new TokenConfigProvider(),
        new CoreConfigProvider(), new RootConfigProvider(),
    ];
    foreach ($providers as $provider) {
        expect($provider())->toBeArray();
    }
    expect((new IdentityConfigProvider())->getRoutes())->not->toBeEmpty()
        ->and((new IdentityConfigProvider())->getDependencies())->toHaveKey('factories')
        ->and((new MailingConfigProvider())->getDependencies())->toHaveKey('aliases');
});
