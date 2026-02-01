<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use Envms\FluentPDO\Query;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Account\Identity\Factory\AccountAccessAuthFactory;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\Factory\RequestPipingFactory;
use Tests\Integration\JsonFactory;

use function expect;

const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3Njk5ODM4MDUsImV4cCI6MTc2OTk4MzgwNiwiaWRlbnQiOiI1NjczOTZhMTg4NTYyNmIwZDBiNGNjMDdmN2EyMDI1OGY2NGRhZTk4YWZmZjk5Y2YyNDVhODdlMTQwM2Q3NzY3NGNiYTBhNzJmZDQwNDM2OGNhODZlNjZlZjc5YjE1NDE4ZjhmOTE4ZjM0YzZhZDk2M2MxOTM4MTU5NTEzNDNlMSJ9.GmrFnwWDlj9uxX7HuDDLQ8HmN6cKTi9XEOmRmuiqOYiu1vZaVw1N1yXxVyMaIc5rJtX2ECcgI84mp5NkLQ_ZbQ';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3Njk5ODM4MDUsImV4cCI6MTc2OTk4MzgwNiwiaWRlbnQiOiI1NjcROTZhMTg4NTYyNmIwZDBiNGNjMDdmN2EyMDI1OGY2NGRhZTk4YWZmZjk5Y2YyNDVhODdlMTQwM2Q3NzY3NGNiYTBhNzJmZDQwNDM2OGNhODZlNjZlZjc5YjE1NDE4ZjhmOTE4ZjM0YzZhZDk2M2MxOTM4MTU5NTEzNDNlMSJ9.GmrFnwWDlj9uxX7HuDDLQ8HmN6cKTi9XEOmRmuiqOYiu1vZaVw1N1yXxVyMaIc5rJtX2ECcgI84mp5NkLQ_ZbQ';
it('returns a new access token', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $response = $this->app->handle($request);
    $json = JsonFactory::create($response);

    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', $json['refreshToken']);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))
        ->toBeArray()
        ->toHaveKey('accessToken');
});

test('Refresh token is invalid', function () {
    $account = AccountFactory::create();
    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', INVALID_TOKEN);

    $modifiedRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $request
    );

    $clientIdentifikation = $modifiedRequest->getAttribute(ClientIdentification::class);

    AccountAccessAuthFactory::create([
        'accountId' => $account['id'],
        'refreshToken' => EXPIRED_TOKEN,
        'clientIdentHash' => $clientIdentifikation->identificationHash,
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Refresh Token is expired', function () {
    $account = AccountFactory::create();
    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', EXPIRED_TOKEN);

    $modifiedRequest = RequestPipingFactory::process(
        ClientIdentificationMiddleware::class,
        $request
    );

    $clientIdentifikation = $modifiedRequest->getAttribute(ClientIdentification::class);

    AccountAccessAuthFactory::create([
        'accountId' => $account['id'],
        'refreshToken' => EXPIRED_TOKEN,
        'clientIdentHash' => $clientIdentifikation->identificationHash,
    ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Refresh token not found', function () {
    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $response = $this->app->handle($request);
    $json = JsonFactory::create($response);

    $request = $this->createGetRequest(
        '/api/token/refresh'
    )
        ->withHeader('Authentication', $json['refreshToken']);

    $fluent = $this->getContainer()->get(Query::class);

    $fluent->delete('AccountAccessAuth')->where('accountId', $account['id'])->execute();

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});
