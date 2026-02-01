<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\Factory\RequestPipingFactory;
use Tests\Integration\JsonFactory;

use function expect;

test('Account has been authenticated and the access and refresh tokens have been returned', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $modifiedRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $request
    );

    $clientIdentifikation = $modifiedRequest->getAttribute(ClientIdentification::class);

    $response = $this->app->handle($request);
    $json = JsonFactory::create($response);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('AccountAccessAuth')->toHaveRecord([
            'accountId' => $account['id'],
            'clientIdentHash' => $clientIdentifikation->identificationHash,
            'refreshToken' => $json['refreshToken'],
        ])
        ->and(JsonFactory::create($response))
        ->toBeArray()
        ->toHaveKey('refreshToken')
        ->toHaveKey('accessToken');
});

test('Login failed because authentication is already set in the header', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    )
        ->withAddedHeader('x-ident', (string)rand())
        ->withAddedHeader('Authorization', 'Authorization');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Login failed because there is already a login entry with the same identification in the database', function () {
    $account = AccountFactory::create();
    $firstRequest = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );
    $secondRequest = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $this->app->handle($firstRequest);
    $response = $this->app->handle($secondRequest);

    expect($response->getStatusCode())->toBe(Http::STATUS_CONFLICT);
});

test('Account can log in with different identifications at the same time', function () {
    $account = AccountFactory::create();
    $firstRequest = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    )
        ->withHeader('x-ident', (string)rand());

    $secondRequest = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    )
        ->withHeader('x-ident', (string)rand());

    $modifiedFirstRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $firstRequest
    );
    $modifiedSecondRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $secondRequest
    );

    $firstClientIdentifikation = $modifiedFirstRequest->getAttribute(ClientIdentification::class);
    $secondClientIdentifikation = $modifiedSecondRequest->getAttribute(ClientIdentification::class);

    $this->app->handle($firstRequest);
    $response = $this->app->handle($secondRequest);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($firstClientIdentifikation->identificationHash)->not->toBe(
            $secondClientIdentifikation->identificationHash
        )
        ->and('AccountAccessAuth')->toHaveRecord([
            'accountId' => $account['id'],
            'clientIdentHash' => $firstClientIdentifikation->identificationHash,
        ])
        ->and('AccountAccessAuth')->toHaveRecord([
            'accountId' => $account['id'],
            'clientIdentHash' => $secondClientIdentifikation->identificationHash,
        ]);
});

test('Credentials have invalid email', function () {
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => Faker::create()->regexify('[a-z]{5}-at-[a-z]{5}\.de'),
            'password' => Faker::create()->password(8),
        ]
    );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Credentials have invalid password', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => Faker::create()->password(1, 5),
        ]
    );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Credentials are missed', function () {
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
        ]
    );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Account missing', function () {
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => Faker::create()->safeEmail(),
            'password' => Faker::create()->password(8),
        ]
    );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});
