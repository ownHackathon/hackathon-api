<?php declare(strict_types=1);

namespace Tests\Integration\Workspace;

use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;

use function expect;

test('Workspace was created', function () {
    $account = $this->createAndLoginUser();
    $name = Faker::create()->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{10,30}');
    $description = Faker::create()->text(50);
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => $name,
            'description' => $description,
        ]
    )
    ->withHeader('Authorization', $account['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
    ->and('Workspace')->toHaveRecord(
        [
                'accountId' => $account['id'],
                'name' => $name,
                'description' => $description,
            ]
    );
});

test('Authorization missed', function () {
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => Faker::create()->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{10,30}'),
            'description' => Faker::create()->text(50),
        ]
    );

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Authorization failed', function () {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => Faker::create()->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{10,30}'),
            'description' => Faker::create()->text(50),
        ]
    )->withHeader('Authorization', $account['refreshToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Workspace name to short', function () {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => '12',
            'description' => Faker::create()->text(50),
        ]
    )->withHeader('Authorization', $account['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('Workspace name to long', function () {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => Faker::create()->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{100,200}'),
            'description' => Faker::create()->text(50),
        ]
    )->withHeader('Authorization', $account['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('Workspace name has invalid characters', function () {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => Faker::create()->regexify('[!@#$%^&*][A-Za-z0-9 _-]{10,30}'),
            'description' => Faker::create()->text(50),
        ]
    )->withHeader('Authorization', $account['accessToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});
