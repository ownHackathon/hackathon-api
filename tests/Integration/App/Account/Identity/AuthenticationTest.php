<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use Envms\FluentPDO\Query;
use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\Factory\RequestPipingFactory;
use Tests\Integration\JsonFactory;

use function expect;
use function str_repeat;
use function test;

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
    $storedAccount = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['id' => $account['id']])
        ->fetch();
    $accessTokenService = $this->getContainer()->get(AccessTokenService::class);
    $refreshTokenService = $this->getContainer()->get(RefreshTokenService::class);
    $accessClaims = $accessTokenService->decode($json['accessToken']);
    $refreshClaims = $refreshTokenService->decode($json['refreshToken']);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($response->getHeaderLine('Content-Type'))->toContain('application/json')
        ->and($json)->toHaveKeys(['accessToken', 'refreshToken'])
        ->and($json['accessToken'])->not->toBeEmpty()
        ->and($json['refreshToken'])->not->toBeEmpty()
        ->and($accessTokenService->isValid($json['accessToken']))->toBeTrue()
        ->and($refreshTokenService->isValid($json['refreshToken']))->toBeTrue()
        ->and($accessClaims->uuid)->toBe($account['uuid'])
        ->and($refreshClaims->ident)->toBe($clientIdentifikation->identificationHash)
        ->and('AccountAccessAuth')->toHaveRecord([
            'accountId' => $account['id'],
            'clientIdentHash' => $clientIdentifikation->identificationHash,
            'refreshToken' => $json['refreshToken'],
        ])
        ->and($storedAccount)->not->toBeFalse()
        ->and($storedAccount['lastActionAt'])->not->toBeNull();
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

test('Login is rejected when the Authentication header is already set', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest('/api/account/authentication', [
        'email' => $account['email'],
        'password' => $account['password'],
    ])->withHeader('Authentication', 'already-authenticated');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toNotHaveRecord(['accountId' => $account['id']]);
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

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and(JsonFactory::create($response)['statusCode'])->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toNotHaveRecord([]);
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

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toNotHaveRecord(['accountId' => $account['id']]);
});

test('Authentication failures do not expose credentials or create a session', function () {
    $account = AccountFactory::create();
    $wrongPassword = 'wrong-secret-value';
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/authentication', [
            'email' => $account['email'],
            'password' => $wrongPassword,
        ])
    );
    $body = (string) $response->getBody();

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and($body)->not->toContain($account['email'])
        ->and($body)->not->toContain($wrongPassword)
        ->and($body)->not->toContain($account['password'])
        ->and('AccountAccessAuth')->toNotHaveRecord(['accountId' => $account['id']]);
});

test('Authentication treats injection-like email input as invalid credentials', function () {
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/authentication', [
            'email' => "' OR 1=1 --@example.com",
            'password' => 'secret',
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and(JsonFactory::create($response))->toHaveSubset([
            'statusCode' => Http::STATUS_UNAUTHORIZED,
        ]);
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

test('Authentication rejects incomplete or incorrectly typed credentials', function (array $credentials) {
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/authentication', $credentials)
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
})->with([
    'missing email' => ['credentials' => ['password' => 'secret']],
    'missing password' => ['credentials' => ['email' => 'user@example.com']],
    'null email' => ['credentials' => ['email' => null, 'password' => 'secret']],
    'null password' => ['credentials' => ['email' => 'user@example.com', 'password' => null]],
    'array email' => ['credentials' => ['email' => ['user@example.com'], 'password' => 'secret']],
    'array password' => ['credentials' => ['email' => 'user@example.com', 'password' => ['secret']]],
]);

test('Authentication accepts the inclusive password boundaries', function (int $length) {
    $password = str_repeat('p', $length);
    $account = AccountFactory::create(['password' => password_hash($password, PASSWORD_BCRYPT)]);
    $request = $this->createJsonPostRequest('/api/account/authentication', [
        'email' => $account['email'],
        'password' => $password,
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK);
})->with([
    'minimum length' => 6,
    'maximum length' => 255,
]);

test('Authentication rejects passwords outside the configured boundaries', function (int $length) {
    $validPassword = 'secret';
    $account = AccountFactory::create(['password' => password_hash($validPassword, PASSWORD_BCRYPT)]);
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/authentication', [
            'email' => $account['email'],
            'password' => str_repeat('p', $length),
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and('AccountAccessAuth')->toNotHaveRecord(['accountId' => $account['id']]);
})->with([
    'below minimum length' => 5,
    'above maximum length' => 256,
]);

test('Authentication trims email and password input', function () {
    $password = 'secret';
    $account = AccountFactory::create(['password' => password_hash($password, PASSWORD_BCRYPT)]);
    $request = $this->createJsonPostRequest('/api/account/authentication', [
        'email' => '  ' . $account['email'] . '  ',
        'password' => '  ' . $password . '  ',
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK);
});

test('Authentication route only accepts POST requests', function () {
    $response = $this->app->handle($this->createGetRequest('/api/account/authentication'));

    expect($response->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('Authentication supports a trailing slash', function () {
    $account = AccountFactory::create();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/authentication/', [
            'email' => $account['email'],
            'password' => $account['password'],
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK);
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
