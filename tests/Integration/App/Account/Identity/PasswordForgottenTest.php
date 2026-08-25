<?php declare(strict_types=1);

namespace Tests\Integration\Account\Identity;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Domain\Exception\HttpHandledInvalidArgumentAsSuccessException;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordService;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;

test('Test successful with valid account', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $account['email']]
    );

    $service = $this->container->get(PasswordService::class);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(200)
        ->and(fn() => $service->sendTokenForInitiateResetPassword(
            EmailType::fromString($account['email'])
        ))
        ->not->toThrow(HttpHandledInvalidArgumentAsSuccessException::class);
});

it('returns 200, despite an invalid email address being provided', function () {
    $email = Faker::create()->safeEmail();

    $request = $this->createJsonPostRequest(
        '/api/account/password/forgotten',
        ['email' => $email]
    );

    $service = $this->container->get(PasswordService::class);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(200)
        ->and(fn() => $service->sendTokenForInitiateResetPassword(
            EmailType::fromString($email)
        ))
        ->toThrow(HttpHandledInvalidArgumentAsSuccessException::class);
});

it('returns 400 when email is invalid', function ($email) {
    $request = $this->createJsonPostRequest(
        '/api/account/password/forgotten',
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
