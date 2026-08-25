<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

test('Account successfully logged out', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $response = $this->app->handle($request);
    $jsonResponse = JsonFactory::create($response);

    $request = $this->createJsonPostRequest(
        '/api/account/logout',
        [
            'refreshToken' => $jsonResponse['refreshToken'],
            ]
    )
        ->withHeader('Authorization', $jsonResponse['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_NO_CONTENT)
        ->and('AccountAccessAuth')->toNotHaveRecord(['refreshToken' => $jsonResponse['refreshToken']]);
});

test('Access Token invalid', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $response = $this->app->handle($request);
    $jsonResponse = JsonFactory::create($response);

    $request = $this->createJsonPostRequest(
        '/api/account/logout',
        [
            'refreshToken' => $jsonResponse['refreshToken'],
        ]
    )
        ->withHeader('Authorization', $jsonResponse['refreshToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Refresh Token invalid', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $response = $this->app->handle($request);
    $jsonResponse = JsonFactory::create($response);
    $request = $this->createJsonPostRequest(
        '/api/account/logout',
        [
            'refreshToken' => $jsonResponse['accessToken'],
        ]
    )
        ->withHeader('Authorization', $jsonResponse['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

it('has not authorization', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $response = $this->app->handle($request);
    $jsonResponse = JsonFactory::create($response);
    $request = $this->createJsonPostRequest(
        '/api/account/logout',
        [
            'refreshToken' => $jsonResponse['accessToken'],
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

it('has no body content', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $response = $this->app->handle($request);
    $jsonResponse = JsonFactory::create($response);
    $request = $this->createJsonPostRequest(
        '/api/account/logout',
        []
    )
        ->withHeader('Authorization', $jsonResponse['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});
