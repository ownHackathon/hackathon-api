<?php declare(strict_types=1);

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\PasswordChangeFactory;
use Tests\Integration\JsonFactory;

test('Password successfully changed', function () {
    $passwordChange = PasswordChangeFactory::create();
    $newPassword = 'new-secure-password';

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => $newPassword,
        ]
    );

    $response = $this->app->handle($request);
    $account = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['id' => $passwordChange['accountId']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toBeArray()->toBeEmpty()
        ->and($account)->not->toBeFalse()
        ->and($account['password'])->not->toBe($newPassword)
        ->and(password_verify($newPassword, $account['password']))->toBeTrue()
        ->and('Token')->toNotHaveRecord(['token' => $passwordChange['token']]);
});

test('Can not use the same token twice', function () {
    $passwordChange = PasswordChangeFactory::create();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $this->app->handle($request);
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toNotHaveRecord(['token' => $passwordChange['token']]);
});

test('Token present in the database but account does not exist', function () {
    $passwordChange = PasswordChangeFactory::create();
    $fluent = $this->getContainer()->get(Query::class);
    $fluent->deleteFrom('Account')->where('id', $passwordChange['accountId'])->execute();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and(JsonFactory::create($response))->toHaveSubset([
            'statusCode' => Http::STATUS_BAD_REQUEST,
        ])
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('Token is invalid', function () {
    $passwordChange = PasswordChangeFactory::create();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . Faker::create('de_DE')->uuid(),
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('Token has incorrect type', function () {
    $passwordChange = PasswordChangeFactory::create([
        'tokenType' => \ownHackathon\App\Token\Domain\Enum\TokenType::Default->value,
    ]);

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('New password invalid', function () {
    $passwordChange = PasswordChangeFactory::create();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(1, 5),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('an invalid password leaves the existing password unchanged', function () {
    $passwordChange = PasswordChangeFactory::create();
    $account = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['id' => $passwordChange['accountId']])
        ->fetch();
    $oldPassword = $account['password'];

    $response = $this->app->handle($this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        ['password' => 'short']
    ));

    $updatedAccount = $this->getContainer()->get(Query::class)
        ->from('Account')
        ->where(['id' => $passwordChange['accountId']])
        ->fetch();

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and($updatedAccount['password'])->toBe($oldPassword)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('password change rejects missing, null and incorrectly typed passwords', function (array $payload) {
    $passwordChange = PasswordChangeFactory::create();
    $response = $this->app->handle(
        $this->createJsonPatchRequest('/api/account/password/' . $passwordChange['token'], $payload)
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
})->with([
    'missing password' => ['payload' => []],
    'null password' => ['payload' => ['password' => null]],
    'array password' => ['payload' => ['password' => ['new-password']]],
]);

test('password change accepts the inclusive password boundaries', function (int $length) {
    $passwordChange = PasswordChangeFactory::create();
    $response = $this->app->handle($this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        ['password' => str_repeat('p', $length)]
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK);
})->with([
    'minimum length' => 6,
    'maximum length' => 255,
]);

test('password change rejects malformed token values without changing an account', function () {
    $passwordChange = PasswordChangeFactory::create();
    $response = $this->app->handle($this->createJsonPatchRequest(
        '/api/account/password/not-a-uuid',
        ['password' => 'new-secure-password']
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('password change does not reveal the reset token in an error response', function () {
    $passwordChange = PasswordChangeFactory::create();
    $response = $this->app->handle($this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        ['password' => 'short']
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and((string) $response->getBody())->not->toContain($passwordChange['token']);
});

test('password change route only accepts PATCH requests', function () {
    $passwordChange = PasswordChangeFactory::create();

    expect($this->app->handle(
        $this->createGetRequest('/api/account/password/' . $passwordChange['token'])
    )->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED)
        ->and('Token')->toHaveRecord(['token' => $passwordChange['token']]);
});

test('password change supports a trailing slash', function () {
    $passwordChange = PasswordChangeFactory::create();
    $response = $this->app->handle($this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'] . '/',
        ['password' => 'new-secure-password']
    ));

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('Token')->toNotHaveRecord(['token' => $passwordChange['token']]);
});
