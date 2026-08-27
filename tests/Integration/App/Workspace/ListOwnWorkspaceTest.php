<?php declare(strict_types=1);

namespace Tests\Integration\Workspace;

use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Workspace\Factory\CreateWorkspacesFactory;
use Tests\Integration\JsonFactory;

use function array_slice;
use function expect;

test('List many Workspaces', function () {
    $account = $this->createAndLoginUser();
    $workspaces = CreateWorkspacesFactory::createManyForOneAccount(7, ['accountId' => $account['id'], 'uuid' => $account['uuid']]);
    $limit = 3;
    $request = $this->createGetRequest('/api/me/workspaces')
        ->withHeader('Authorization', $account['accessToken'])
        ->withQueryParams([
            'limit' => (string) $limit,
            'page' => '1',
        ]);


    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
    ->and(JsonFactory::create($response))
        ->toBeArray()

        ->toHaveSubset(createResponseForManyWorkspaces($workspaces, $limit));
});

test('an unauthenticated request cannot list workspaces', function () {
    $response = $this->app->handle($this->createGetRequest('/api/me/workspaces'));

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('an invalid or refresh token cannot list workspaces', function (string $token) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $token === 'refresh' ? $account['refreshToken'] : 'not-a-jwt')
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
})->with([
    'invalid token' => 'invalid',
    'refresh token' => 'refresh',
]);

test('the list only contains workspaces of the authenticated account', function () {
    $account = $this->createAndLoginUser();
    $otherAccount = \Tests\Integration\App\Factory\AccountFactory::create();
    $ownWorkspace = CreateWorkspacesFactory::create(['accountId' => $account['id']]);
    CreateWorkspacesFactory::create(['accountId' => $otherAccount['id']]);

    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data['meta'])->toHaveSubset(['totalItems' => 1])
        ->and($data['workspaces'])->toHaveCount(1)
        ->and($data['workspaces'][0])->toHaveSubset([
            'uuid' => $ownWorkspace['uuid'],
            'ownerUuid' => $account['uuid'],
        ]);
});

test('an account without workspaces receives an empty first page', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data)->toHaveKeys(['workspaces', 'meta'])
        ->and($data['workspaces'])->toBeEmpty()
        ->and($data['meta'])->toBe([
            'currentPage' => 1,
            'totalPages' => 1,
            'totalItems' => 0,
        ]);
});

test('pagination returns the requested page and correct metadata', function () {
    $account = $this->createAndLoginUser();
    $workspaces = CreateWorkspacesFactory::createManyForOneAccount(5, ['accountId' => $account['id']]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $account['accessToken'])
            ->withQueryParams(['page' => '2', 'limit' => '2'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data['meta'])->toBe([
            'currentPage' => 2,
            'totalPages' => 3,
            'totalItems' => 5,
        ])
        ->and($data['workspaces'])->toHaveCount(2)
        ->and($data['workspaces'][0]['uuid'])->toBe($workspaces[2]['uuid'])
        ->and($data['workspaces'][1]['uuid'])->toBe($workspaces[3]['uuid']);
});

test('a page beyond the last page returns no workspaces but keeps metadata', function () {
    $account = $this->createAndLoginUser();
    CreateWorkspacesFactory::createManyForOneAccount(2, ['accountId' => $account['id']]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $account['accessToken'])
            ->withQueryParams(['page' => '3', 'limit' => '2'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data['workspaces'])->toBeEmpty()
        ->and($data['meta'])->toBe([
            'currentPage' => 3,
            'totalPages' => 1,
            'totalItems' => 2,
        ]);
});

test('pagination clamps unsafe page and limit values', function () {
    $account = $this->createAndLoginUser();
    CreateWorkspacesFactory::createManyForOneAccount(3, ['accountId' => $account['id']]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces')
            ->withHeader('Authorization', $account['accessToken'])
            ->withQueryParams(['page' => '-10', 'limit' => '999 OR 1=1'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data['meta'])->toHaveSubset([
            'currentPage' => 1,
            'totalPages' => 1,
            'totalItems' => 3,
        ])
        ->and($data['workspaces'])->toHaveCount(3);
});

test('workspace list has a stable response contract and supports a trailing slash', function () {
    $account = $this->createAndLoginUser();
    CreateWorkspacesFactory::create([
        'accountId' => $account['id'],
        'description' => null,
    ]);
    $response = $this->app->handle(
        $this->createGetRequest('/api/me/workspaces/')
            ->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($data)->toHaveKeys(['workspaces', 'meta'])
        ->and($data['workspaces'][0])->toHaveKeys(['uuid', 'ownerUuid', 'name', 'slug', 'description'])
        ->and($data['workspaces'][0]['description'])->toBeNull();
});

test('workspace list only accepts GET requests', function () {
    expect($this->app->handle($this->createJsonPostRequest('/api/me/workspaces', []))
        ->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
});

function createResponseForManyWorkspaces(array $workspaces, int $itemsPerPage): array
{
    $response = [
        'workspaces' => [],
        'meta' => [
            'currentPage' => 1,
            'totalPages' => max(1, (int)ceil(count($workspaces) / $itemsPerPage)),
            'totalItems' => count($workspaces),
        ],
    ];

    $workspaces = array_slice($workspaces, 0, $itemsPerPage);

    foreach ($workspaces as $workspace) {
        unset($workspace['id'], $workspace['accountId']);
        $response['workspaces'][] = $workspace;
    }

    return $response;
}
