<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

test('Account successfully logged out', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $tokens['refreshToken'],
        ])->withHeader('Authorization', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_NO_CONTENT)
        ->and((string) $response->getBody())->toBe('')
        ->and('AccountAccessAuth')->toNotHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('a logged out refresh token cannot be used a second time', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);
    $logoutRequest = $this->createJsonPostRequest('/api/account/logout', [
        'refreshToken' => $tokens['refreshToken'],
    ])->withHeader('Authorization', $tokens['accessToken']);

    $this->app->handle($logoutRequest);
    $secondResponse = $this->app->handle($logoutRequest);

    expect($secondResponse->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and(JsonFactory::create($secondResponse))->toHaveSubset([
            'statusCode' => Http::STATUS_UNAUTHORIZED,
        ]);
});

test('logout rejects an invalid access token and keeps the session', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $tokens['refreshToken'],
        ])->withHeader('Authorization', $tokens['refreshToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('logout rejects an invalid refresh token and keeps the session', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $tokens['accessToken'],
        ])->withHeader('Authorization', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('logout requires an access token and keeps the session', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle($this->createJsonPostRequest('/api/account/logout', [
        'refreshToken' => $tokens['refreshToken'],
    ]));

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});

test('logout rejects a refresh token from a different client', function () {
    $account = AccountFactory::create();
    $login = function (string $ident) use ($account): array {
        $response = $this->app->handle($this->createJsonPostRequest(
            '/api/account/authentication',
            ['email' => $account['email'], 'password' => $account['password']]
        )->withHeader('x-ident', $ident));

        return JsonFactory::create($response);
    };
    $firstSession = $login('logout-client-one');
    $secondSession = $login('logout-client-two');

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $firstSession['refreshToken'],
        ])->withHeader('Authorization', $secondSession['accessToken'])
            ->withHeader('x-ident', 'logout-client-two')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $firstSession['refreshToken']])
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $secondSession['refreshToken']]);
});

test('logout rejects a refresh token belonging to another account', function () {
    $firstAccount = AccountFactory::create();
    $secondAccount = AccountFactory::create();
    $firstLogin = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $firstAccount['email'], 'password' => $firstAccount['password']]
    )->withHeader('x-ident', 'logout-owner'));
    $secondLogin = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $secondAccount['email'], 'password' => $secondAccount['password']]
    )->withHeader('x-ident', 'logout-attacker'));
    $firstTokens = JsonFactory::create($firstLogin);
    $secondTokens = JsonFactory::create($secondLogin);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $firstTokens['refreshToken'],
        ])->withHeader('Authorization', $secondTokens['accessToken'])
            ->withHeader('x-ident', 'logout-attacker')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $firstTokens['refreshToken']]);
});

test('logout does not reveal token values in error responses', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);
    $invalidToken = 'invalid-refresh-token';

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', [
            'refreshToken' => $invalidToken,
        ])->withHeader('Authorization', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and((string) $response->getBody())->not->toContain($invalidToken);
});

test('logout rejects an empty or null refresh token', function (array $body) {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout', $body)
            ->withHeader('Authorization', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toHaveRecord(['refreshToken' => $tokens['refreshToken']]);
})->with([
    'empty string' => [['refreshToken' => '']],
    'null value' => [['refreshToken' => null]],
]);

test('logout route only accepts POST requests', function () {
    expect($this->app->handle($this->createGetRequest('/api/account/logout'))
        ->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('logout supports a trailing slash', function () {
    $account = AccountFactory::create();
    $loginResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/authentication',
        ['email' => $account['email'], 'password' => $account['password']]
    ));
    $tokens = JsonFactory::create($loginResponse);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/logout/', [
            'refreshToken' => $tokens['refreshToken'],
        ])->withHeader('Authorization', $tokens['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_NO_CONTENT)
        ->and('AccountAccessAuth')->toNotHaveRecord(['refreshToken' => $tokens['refreshToken']]);
});
