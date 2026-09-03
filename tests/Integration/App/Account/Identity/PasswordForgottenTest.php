<?php declare(strict_types=1);

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

test('password reset request creates a persistent email token for an existing account', function () {
    $account = AccountFactory::create();

    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $account['email']]
    ));
    $token = $this->getContainer()->get(Query::class)
        ->from('Token')
        ->where(['accountId' => $account['id']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toBeArray()->toBeEmpty()
        ->and($token)->not->toBeFalse()
        ->and($token['accountId'])->toBe((int) $account['id'])
        ->and($token['tokenType'])->toBe(2)
        ->and($token['token'])->toMatch('/^[0-9a-f-]{36}$/i')
        ->and($token['createdAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/');
});

test('unknown accounts receive the same successful response without a token', function () {
    $existingAccount = AccountFactory::create();
    $unknownEmail = Faker::create()->safeEmail();

    $existingResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $existingAccount['email']]
    ));
    $unknownResponse = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $unknownEmail]
    ));

    expect($existingResponse->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($unknownResponse->getStatusCode())->toBe(Http::STATUS_OK)
        ->and((string) $existingResponse->getBody())->toBe((string) $unknownResponse->getBody())
        ->and($this->getContainer()->get(Query::class)->from('Token')->fetchAll())->toHaveCount(1);
});

test('password reset request trims a valid email address', function () {
    $account = AccountFactory::create();

    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => '  ' . $account['email'] . '  ']
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($this->getContainer()->get(Query::class)->from('Token')
            ->where(['accountId' => $account['id']])->fetch())->not->toBeFalse();
});

test('invalid email input is rejected without creating a token', function (string $email) {
    $account = AccountFactory::create();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $email]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and($this->getContainer()->get(Query::class)->from('Token')->fetchAll())->toHaveCount(0);
})->with([
    'missing local part' => '@example.com',
    'missing domain' => 'user@',
    'invalid hostname' => 'user@-domain.com',
    'double dot' => 'user..name@example.com',
    'whitespace' => 'user name@example.com',
    'injection-like value' => "' OR 1=1 --@example.com",
]);

test('password reset request rejects missing, null and incorrectly typed email values', function (array $payload) {
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/account/password/forgotten', $payload)
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and($this->getContainer()->get(Query::class)->from('Token')->fetchAll())->toHaveCount(0);
})->with([
    'missing email' => [[]],
    'null email' => [['email' => null]],
    'array email' => [['email' => ['user@example.com']]],
]);

test('password reset errors do not reveal the submitted email', function () {
    $email = 'not-an-email@example.invalid';
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $email]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and((string) $response->getBody())->not->toContain($email);
});

test('password reset route only accepts POST requests', function () {
    expect($this->app->handle($this->createGetRequest('/api/account/password/forgotten'))
        ->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('password reset request supports a trailing slash', function () {
    $account = AccountFactory::create();
    $response = $this->app->handle($this->createJsonPostRequest(
        '/api/account/password/forgotten/',
        ['email' => $account['email']]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($this->getContainer()->get(Query::class)->from('Token')
            ->where(['accountId' => $account['id']])->fetch())->not->toBeFalse();
});
