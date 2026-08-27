<?php declare(strict_types=1);

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use ownHackathon\App\Account\Identity\Application\Port\AccountRegisterServiceInterface;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

test('registration creates an activation token for a new email address', function () {
    $email = Faker::create()->safeEmail();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => $email]
    ));
    $activation = $this->getContainer()->get(Query::class)
        ->from('AccountActivation')
        ->where(['email' => $email])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toBeArray()->toBeEmpty()
        ->and($activation)->not->toBeFalse()
        ->and($activation['email'])->toBe($email)
        ->and($activation['token'])->toMatch('/^[0-9a-f-]{36}$/i')
        ->and($activation['createdAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/')
        ->and($this->getContainer()->get(Query::class)->from('Account')
            ->where(['email' => $email])->fetch())->toBeFalse();
});

test('registration does not reveal that an email already has an account', function () {
    $account = AccountFactory::create();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => $account['email']]
    ));
    $resetToken = $this->getContainer()->get(Query::class)
        ->from('Token')
        ->where(['accountId' => $account['id']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toBeArray()->toBeEmpty()
        ->and($resetToken)->not->toBeFalse()
        ->and($resetToken['tokenType'])->toBe(2)
        ->and($resetToken['accountId'])->toBe((int) $account['id'])
        ->and($this->getContainer()->get(Query::class)->from('AccountActivation')
            ->where(['email' => $account['email']])->fetch())->toBeFalse();
});

test('registration trims a valid email address', function () {
    $email = Faker::create()->safeEmail();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => '  ' . $email . '  ']
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('AccountActivation')->toHaveRecord(['email' => $email]);
});

test('invalid email input is rejected without creating an activation token', function (string $email) {
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => $email]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and($this->getContainer()->get(Query::class)->from('AccountActivation')->fetchAll())
        ->toHaveCount(0)
        ->and($this->getContainer()->get(Query::class)->from('Token')->fetchAll())
        ->toHaveCount(0);
})->with([
    'missing local part' => '@example.com',
    'missing domain' => 'user@',
    'invalid hostname' => 'user@-domain.com',
    'double dot' => 'user..name@example.com',
    'whitespace' => 'user name@example.com',
    'injection-like value' => "' OR 1=1 --@example.com",
]);

test('registration rejects missing, null and incorrectly typed email values', function (array $payload) {
    $response = $this->app->handle($this->createJsonPostRequest('/api/account', $payload));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and($this->getContainer()->get(Query::class)->from('AccountActivation')->fetchAll())
        ->toHaveCount(0);
})->with([
    'missing email' => ['payload' => []],
    'null email' => ['payload' => ['email' => null]],
    'array email' => ['payload' => ['email' => ['user@example.com']]],
]);

test('registration errors do not reveal the submitted email', function () {
    $email = "' OR 1=1 --@example.com";
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => $email]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and((string) $response->getBody())->not->toContain($email);
});

test('unexpected registration failures return a generic server error', function () {
    $service = Mockery::mock(AccountRegisterServiceInterface::class);
    $service->shouldReceive('register')->andThrow(new Exception('Database connection failed'));
    $this->container->setService(AccountRegisterServiceInterface::class, $service);

    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account',
        ['email' => Faker::create()->safeEmail()]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and((string) $response->getBody())->not->toContain('Database connection failed');
});

test('registration route only accepts POST requests', function () {
    expect($this->app->handle($this->createGetRequest('/api/account'))
        ->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('registration supports a trailing slash', function () {
    $email = Faker::create()->safeEmail();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/',
        ['email' => $email]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('AccountActivation')->toHaveRecord(['email' => $email]);
});
