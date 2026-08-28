<?php declare(strict_types=1);

namespace Tests\Integration\App\Workspace;

use Fig\Http\Message\StatusCodeInterface as Http;
use Ramsey\Uuid\Uuid;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use Tests\Integration\App\Factory\AccountFactory;
use Tests\Integration\App\Workspace\Factory\CreateWorkspacesFactory;
use Tests\Integration\JsonFactory;
use ownHackathon\Core\SharedKernel\Domain\Enum\Visibility;

use function array_keys;
use function expect;
use function test;

test('authenticated user can retrieve a workspace by slug', function () {
    $account = $this->createAndLoginUser();
    $workspace = CreateWorkspacesFactory::create(['accountId' => $account['id']]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toHaveSubset([
            'name' => $workspace['name'],
            'owner' => $account['name'],
            'ownerUuid' => $account['uuid'],
        ]);
});

test('public workspace details can be read without authentication', function () {
    $owner = AccountFactory::create();
    $workspace = CreateWorkspacesFactory::create([
        'accountId' => $owner['id'],
        'visibility' => Visibility::PUBLIC->value,
    ]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toHaveSubset(['name' => $workspace['name']]);
});

test('private workspace details are hidden from unauthenticated users', function () {
    $owner = AccountFactory::create();
    $workspace = CreateWorkspacesFactory::create([
        'accountId' => $owner['id'] + 1,
        'visibility' => Visibility::UNLISTED->value,
    ]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_NOT_FOUND);
});

test('workspace with invalid visibility remains readable without causing a server error', function () {
    $account = $this->createAndLoginUser();
    $workspace = CreateWorkspacesFactory::create([
        'accountId' => $account['id'],
        'visibility' => Visibility::PUBLIC->value + 1,
    ]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response)['visibility'])->toBe(Visibility::UNLISTED->value);
});

test('authenticated workspace lookup returns not found for an unknown slug', function () {
    $account = $this->createAndLoginUser();

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/unknown-workspace')
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_NOT_FOUND)
        ->and(JsonFactory::create($response))->toHaveSubset([
            'statusCode' => Http::STATUS_NOT_FOUND,
            'message' => 'Workspace not found',
        ]);
});

test('workspace details reject an invalid access token', function () {
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/unknown-workspace')
            ->withHeader('Authorization', 'not-a-jwt')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED)
        ->and(JsonFactory::create($response)['statusCode'])->toBe(Http::STATUS_UNAUTHORIZED);
});

test('workspace details reject a refresh token as access token', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/unknown-workspace')
            ->withHeader('Authorization', $account['refreshToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('workspace detail route rejects methods other than GET', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace/some-workspace', [])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

test('workspace details reject an invalid authorization value', function () {
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/unknown-workspace')
            ->withHeader('Authorization', 'Bearer not-a-jwt')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('workspace details support a trailing slash', function () {
    $account = $this->createAndLoginUser();
    $workspace = CreateWorkspacesFactory::create(['accountId' => $account['id']]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'] . '/')
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and(JsonFactory::create($response))->toHaveSubset(['name' => $workspace['name']]);
});

test('workspace details return not found for malformed slugs', function (string $slug) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $slug)
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBeIn([Http::STATUS_NOT_FOUND, Http::STATUS_METHOD_NOT_ALLOWED]);
})->with([
    'contains whitespace' => 'not found',
    'contains a slash' => 'not/found',
    'contains a dot' => 'not.found',
    'is empty' => '',
]);

test('workspace details return nullable fields and visibility', function () {
    $account = $this->createAndLoginUser();
    $workspace = CreateWorkspacesFactory::create([
        'accountId' => $account['id'],
        'description' => null,
        'details' => null,
        'visibility' => Visibility::UNLISTED->value,
    ]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
            ->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data)->toHaveKeys(['name', 'description', 'owner', 'ownerUuid', 'details', 'visibility', 'createdAt', 'updatedAt'])
        ->and($data['description'])->toBe('')
        ->and($data['details'])->toBeNull()
        ->and($data['visibility'])->toBe(Visibility::UNLISTED->value);
});

test('workspace details can be read by another authenticated account without changing the owner', function () {
    $owner = $this->createAndLoginUser();
    $visitor = AccountFactory::create();
    $visitorToken = $this->getContainer()->get(AccessTokenService::class)
        ->generate(Uuid::fromString($visitor['uuid']))
        ->accessToken;
    $workspace = CreateWorkspacesFactory::create(['accountId' => $owner['id']]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'])
            ->withHeader('Authorization', $visitorToken)
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data)->toHaveSubset([
            'owner' => $owner['name'],
            'ownerUuid' => $owner['uuid'],
        ])
        ->and($data['owner'])->not->toBe($visitor['name'])
        ->and($data['ownerUuid'])->not->toBe($visitor['uuid']);
});

test('workspace details preserve all supported visibility values', function () {
    $account = $this->createAndLoginUser();
    $visibilities = [Visibility::UNLISTED->value, Visibility::REGISTERED->value, Visibility::PUBLIC->value];

    foreach ($visibilities as $visibility) {
        $workspace = CreateWorkspacesFactory::create([
            'accountId' => $account['id'],
            'visibility' => $visibility,
        ]);
        $response = $this->app->handle(
            $this->createGetRequest('/api/workspace/' . $workspace['slug'])
                ->withHeader('Authorization', $account['accessToken'])
        );

        expect($response->getStatusCode())->toBe(Http::STATUS_OK)
            ->and(JsonFactory::create($response)['visibility'])->toBe($visibility);
    }
});

test('workspace details return a stable JSON contract including formatted timestamps', function () {
    $account = $this->createAndLoginUser();
    $workspace = CreateWorkspacesFactory::create(['accountId' => $account['id']]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/workspace/' . $workspace['slug'] . '?unused=1')
            ->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getHeaderLine('Content-Type'))->toContain('application/json')
        ->and(array_keys($data))->toEqual([
            'name',
            'description',
            'owner',
            'ownerUuid',
            'details',
            'visibility',
            'createdAt',
            'updatedAt',
        ])
        ->and($data['createdAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/')
        ->and($data['updatedAt'])->toMatch('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/');
});
