<?php declare(strict_types=1);

namespace Tests\Integration\Account\Identity;

use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountActivationFactory;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;

test('Account creation successfully completed', function () {
    $accountActivate = AccountActivationFactory::create();

    $request = $this->createJsonPostRequest(
        '/api/account/activation/' . $accountActivate['token'],
        [
            'accountName' => Faker::create()->unique()->name(),
            'password' => Faker::create()->password(8),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and('Account')->toHaveRecord(['email' => $accountActivate['email']]);
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
    expect($response->getStatusCode())->toBe(Http::STATUS_CONFLICT);
});
