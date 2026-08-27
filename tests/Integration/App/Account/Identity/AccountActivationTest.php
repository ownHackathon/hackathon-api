<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountActivationFactory;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

use function expect;
use function test;

test('Account creation successfully completed', function () {
    $accountActivate = AccountActivationFactory::create();
    $accountName = Faker::create()->unique()->name();
    $password = Faker::create()->password(8);

    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivate['token'],
        [
            'accountName' => $accountName,
            'password' => $password,
        ]
    );
    $response = $this->app->handle($request);
    $data = JsonFactory::create($response);
    $account = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['email' => $accountActivate['email']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and($response->getHeaderLine('Location'))->toBe('/api/account/' . $data['uuid'] . '/')
        ->and($data)->toHaveKeys(['uuid', 'name', 'email', 'registeredAt', 'lastActionAt'])
        ->and($data)->toHaveSubset([
            'name' => $accountName,
            'email' => $accountActivate['email'],
        ])
        ->and($data['uuid'])->toMatch('/^[0-9a-f-]{36}$/i')
        ->and($data['registeredAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/')
        ->and($data['lastActionAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/')
        ->and($account)->not->toBeFalse()
        ->and($account['name'])->toBe($accountName)
        ->and($account['email'])->toBe($accountActivate['email'])
        ->and($account['password'])->not->toBe($password)
        ->and(password_verify($password, $account['password']))->toBeTrue()
        ->and('AccountActivation')->toNotHaveRecord(['id' => $accountActivate['id']]);
});

test('an activation token can only be used once', function () {
    $accountActivation = AccountActivationFactory::create();
    $payload = [
        'accountName' => Faker::create()->unique()->name(),
        'password' => Faker::create()->password(8),
    ];

    $firstResponse = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], $payload)
    );
    $secondResponse = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], $payload)
    );

    expect($firstResponse->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and($secondResponse->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and(JsonFactory::create($secondResponse))->toHaveSubset([
            'statusCode' => Http::STATUS_BAD_REQUEST,
            'message' => 'Invalid token',
        ])
        ->and('Account')->toHaveRecord([
            'email' => $accountActivation['email'],
            'name' => $payload['accountName'],
        ]);
});

it('returns 400 when body is empty', function () {
    $accountActivation = AccountActivationFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivation['token'],
        [],
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('returns 400 when an activation field is missing or has the wrong type', function (array $payload) {
    $accountActivation = AccountActivationFactory::create();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], $payload)
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Account')->toNotHaveRecord(['email' => $accountActivation['email']])
        ->and('AccountActivation')->toHaveRecord(['id' => $accountActivation['id']]);
})->with([
    'missing account name' => ['payload' => ['password' => 'secret']],
    'missing password' => ['payload' => ['accountName' => 'Valid Name']],
    'null account name' => ['payload' => ['accountName' => null, 'password' => 'secret']],
    'null password' => ['payload' => ['accountName' => 'Valid Name', 'password' => null]],
    'array account name' => ['payload' => ['accountName' => ['name'], 'password' => 'secret']],
    'array password' => ['payload' => ['accountName' => 'Valid Name', 'password' => ['secret']]],
]);

it('returns 400 when not token was assigned', function () {
    $request = $this->createJsonPostRequest(
        '/api/account/activation/',
        [
            'accountName' => Faker::create()->unique()->name(),
            'password' => Faker::create()->password(8),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

it('returns 400 if the account name is invalid', function () {
    $accountActivation = AccountActivationFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivation['token'],
        [
            'accountName' => Faker::create()->lexify('??'),
            'password' => Faker::create()->password(8),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

it('returns 400 if the password is invalid', function () {
    $accountActivation = AccountActivationFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivation['token'],
        [
            'accountName' => Faker::create()->name(),
            'password' => Faker::create()->password(1, 5),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('accepts the inclusive account name and password boundaries', function () {
    $accountActivation = AccountActivationFactory::create();
    $accountName = str_repeat('A', 64);
    $password = str_repeat('p', 6);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], [
            'accountName' => $accountName,
            'password' => $password,
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and('Account')->toHaveRecord([
            'email' => $accountActivation['email'],
            'name' => $accountName,
        ]);
});

test('rejects values outside the account name and password boundaries', function (string $accountName, string $password) {
    $accountActivation = AccountActivationFactory::create();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], [
            'accountName' => $accountName,
            'password' => $password,
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('AccountActivation')->toHaveRecord(['id' => $accountActivation['id']]);
})->with([
    'account name too short' => [str_repeat('A', 2), 'secret'],
    'account name too long' => [str_repeat('A', 65), 'secret'],
    'password too short' => ['Valid Name', str_repeat('p', 5)],
    'password too long' => ['Valid Name', str_repeat('p', 256)],
]);

test('trims account name and password before persistence', function () {
    $accountActivation = AccountActivationFactory::create();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], [
            'accountName' => '  Trimmed Name  ',
            'password' => '  secret  ',
        ])
    );
    $account = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['email' => $accountActivation['email']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and($account['name'])->toBe('Trimmed Name')
        ->and(password_verify('secret', $account['password']))->toBeTrue();
});

it('returns 400 because token not found', function () {
    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . Faker::create()->uuid(),
        [
            'accountName' => Faker::create()->name(),
            'password' => Faker::create()->password(8),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

it('returns 409 when Accountname is duplicate', function () {
    $accountActivation = AccountActivationFactory::create();
    $account = AccountFactory::create();

    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivation['token'],
        [
            'accountName' => $account['name'],
            'password' => Faker::create()->password(8),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_CONFLICT)
        ->and(JsonFactory::create($response))->toHaveSubset([
            'statusCode' => Http::STATUS_CONFLICT,
        ])
        ->and('AccountActivation')->toHaveRecord(['id' => $accountActivation['id']]);
});

test('returns 409 when the activation email already belongs to an account', function () {
    $account = AccountFactory::create();
    $accountActivation = AccountActivationFactory::create(['email' => $account['email']]);

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/activation/' . $accountActivation['token'], [
            'accountName' => Faker::create()->unique()->name(),
            'password' => Faker::create()->password(8),
        ])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CONFLICT)
        ->and('AccountActivation')->toHaveRecord(['id' => $accountActivation['id']]);
});

test('activation route only accepts POST requests', function () {
    $accountActivation = AccountActivationFactory::create();
    $response = $this->app->handle(
        $this->createGetRequest('/api/account/activation/' . $accountActivation['token'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED)
        ->and('AccountActivation')->toHaveRecord(['id' => $accountActivation['id']]);
});
