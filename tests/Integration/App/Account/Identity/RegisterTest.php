<?php declare(strict_types=1);

namespace Tests\Integration\Account\Identity\Register;

use Exception;
use Exdrals\Identity\Infrastructure\Service\Account\AccountRegisterServiceInterface;
use Faker\Factory as Faker;
use Mockery;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;

test('Valid email address and activation token sent', function () {
    $email = Faker::create()->safeEmail();
    $request = $this->createJsonPostRequest(
        '/api/account',
        [
            'email' => $email,
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('AccountActivation')->toHaveRecord(['email' => $email]);
});


it('returns 400 when email is invalid', function ($email) {
    $request = $this->createJsonPostRequest(
        '/api/account',
        [
            'email' => $email,
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
})->with([
    'invalidate.com',
    'user@',
    '@domain.com',
    'user..name@domain.com',
    'user:name@domain.com',
    'user@domain!.com',
    'user@domain',
    'user name@domain.com',
    'user@domain .com',
    'user@domain..com',
    'user@-domain.com',
    'test@example.invalid',
    'test@example.web',
]);

it('handles service exceptions through mapping middleware', function () {
    $service = Mockery::mock(AccountRegisterServiceInterface::class);
    $service->shouldReceive('register')
        ->andThrow(new Exception('Database connection failed'));
    $this->container->setService(AccountRegisterServiceInterface::class, $service);
    $request = $this->createJsonPostRequest(
        '/api/account',
        [
            'email' => Faker::create()->safeEmail(),
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK);
});

it('returns 400 when body is empty', function () {
    $request = $this->createJsonPostRequest(
        '/api/account',
        [],
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

it('returns 400 when email address is duplicate', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account',
        [
            'email' => $account['email'],
        ]
    );
    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and('Account')->toHaveRecord(['email' => $account['email']]);
});
