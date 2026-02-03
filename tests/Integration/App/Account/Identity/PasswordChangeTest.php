<?php declare(strict_types=1);

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\PasswordChangeFactory;

test('Password successfully changed', function () {
    $passwordChange = PasswordChangeFactory::create();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
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
    $fluent->deleteFrom('Token')->where('accountId', $passwordChange['accountId'])->execute();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('Token is invalid', function () {
    PasswordChangeFactory::create();

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . Faker::create('de_DE')->uuid(),
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('Token has incorrect type', function () {
    $passwordChange = PasswordChangeFactory::create([
        'tokenType' => \Exdrals\Shared\Domain\Enum\Token\TokenType::Default->value,
    ]);

    $request = $this->createJsonPatchRequest(
        '/api/account/password/' . $passwordChange['token'],
        [
            'password' => Faker::create('de_DE')->password(8),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
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
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});
