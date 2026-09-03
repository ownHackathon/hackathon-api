<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use Envms\FluentPDO\Query;
use Fig\Http\Message\StatusCodeInterface as Http;
use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Tests\Integration\App\Account\Identity\Factory\AccountAccessAuthFactory;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\Factory\RequestPipingFactory;
use Tests\Integration\JsonFactory;

use function expect;

// phpcs:disable Generic.Files.LineLength.MaxExceeded
const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3Njk5ODM4MDUsImV4cCI6MTc2OTk4MzgwNiwiaWRlbnQiOiI1NjczOTZhMTg4NTYyNmIwZDBiNGNjMDdmN2EyMDI1OGY2NGRhZTk4YWZmZjk5Y2YyNDVhODdlMTQwM2Q3NzY3NGNiYTBhNzJmZDQwNDM2OGNhODZlNjZlZjc5YjE1NDE4ZjhmOTE4ZjM0YzZhZDk2M2MxOTM4MTU5NTEzNDNlMSJ9.GmrFnwWDlj9uxX7HuDDLQ8HmN6cKTi9XEOmRmuiqOYiu1vZaVw1N1yXxVyMaIc5rJtX2ECcgI84mp5NkLQ_ZbQ';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3Njk5ODM4MDUsImV4cCI6MTc2OTk4MzgwNiwiaWRlbnQiOiI1NjcROTZhMTg4NTYyNmIwZDBiNGNjMDdmN2EyMDI1OGY2NGRhZTk4YWZmZjk5Y2YyNDVhODdlMTQwM2Q3NzY3NGNiYTBhNzJmZDQwNDM2OGNhODZlNjZlZjc5YjE1NDE4ZjhmOTE4ZjM0YzZhZDk2M2MxOTM4MTU5NTEzNDNlMSJ9.GmrFnwWDlj9uxX7HuDDLQ8HmN6cKTi9XEOmRmuiqOYiu1vZaVw1N1yXxVyMaIc5rJtX2ECcgI84mp5NkLQ_ZbQ';
it('returns a new access token', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $response = $this->app->handle($request);
    $json = JsonFactory::create($response);

    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', $json['refreshToken']);

    $response = $this->app->handle($request);
    $refreshed = JsonFactory::create($response);
    $accessTokenService = $this->getContainer()->get(AccessTokenService::class);
    $refreshTokenService = $this->getContainer()->get(RefreshTokenService::class);
    $claims = $accessTokenService->decode($refreshed['accessToken']);
    $refreshClaims = $refreshTokenService->decode($json['refreshToken']);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($refreshed)->toHaveKeys(['accessToken'])
        ->and($accessTokenService->isValid($refreshed['accessToken']))->toBeTrue()
        ->and($claims->uuid)->toBe($account['uuid'])
        ->and($refreshClaims->ident)->not->toBeEmpty();
});

test('Refresh token is invalid', function () {
    $account = AccountFactory::create();
    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', INVALID_TOKEN);

    $modifiedRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $request
    );

    $clientIdentifikation = $modifiedRequest->getAttribute(ClientIdentification::class);

    AccountAccessAuthFactory::create([
        'accountId' => $account['id'],
        'refreshToken' => EXPIRED_TOKEN,
        'clientIdentHash' => $clientIdentifikation->identificationHash,
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Refresh Token is expired', function () {
    $account = AccountFactory::create();
    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', EXPIRED_TOKEN);

    $modifiedRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $request
    );

    $clientIdentifikation = $modifiedRequest->getAttribute(ClientIdentification::class);

    AccountAccessAuthFactory::create([
        'accountId' => $account['id'],
        'refreshToken' => EXPIRED_TOKEN,
        'clientIdentHash' => $clientIdentifikation->identificationHash,
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Refresh token not found', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $response = $this->app->handle($request);
    $json = JsonFactory::create($response);

    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', $json['refreshToken']);

    $fluent = $this->getContainer()->get(Query::class);

    $fluent->delete('AccountAccessAuth')->where('accountId', $account['id'])->execute();

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('refresh requires the Authentication header and not Authorization', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh')
            ->withHeader('Authorization', $tokens['refreshToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('refresh rejects a token used from a different client', function () {
    $account = AccountFactory::create();
    $firstLogin = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    )->withHeader('x-ident', 'refresh-client-one'));
    $tokens = JsonFactory::create($firstLogin);

    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh')
            ->withHeader('Authentication', $tokens['refreshToken'])
            ->withHeader('x-ident', 'refresh-client-two')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('refresh rejects a persisted token when its account no longer exists', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);
    $this->getContainer()->get(Query::class)
        ->deleteFrom('Account')
        ->where(['id' => $account['id']])
        ->execute();

    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh')
            ->withHeader('Authentication', $tokens['refreshToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('refresh rejects an access token used as refresh token', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh')
            ->withHeader('Authentication', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('refresh errors do not reveal the submitted token', function () {
    $invalidToken = 'not-a-real-refresh-token';
    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh')
            ->withHeader('Authentication', $invalidToken)
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and((string) $response->getBody())->not->toContain($invalidToken);
});

test('refresh route only accepts GET requests', function () {
    expect($this->app->handle($this->createJsonPostRequest('/api/token/refresh', []))
        ->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('refresh supports a trailing slash', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createGetRequest('/api/token/refresh/')
            ->withHeader('Authentication', $tokens['refreshToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toHaveKey('accessToken');
});
