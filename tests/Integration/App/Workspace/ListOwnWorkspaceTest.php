<?php declare(strict_types=1);

namespace Tests\Integration\Workspace;

use Fig\Http\Message\StatusCodeInterface as Http;
use Tests\Integration\App\Workspace\Factory\CreateWorkspacesFactory;
use Tests\Integration\JsonFactory;

use function array_slice;
use function expect;
use function rand;

test('List many Workspaces', function () {
    $account = $this->createAndLoginUser();
    $workspaces = CreateWorkspacesFactory::createManyForOneAccount(rand(1, 50), ['accountId' => $account['id'], 'uuid' => $account['uuid']]);
    $limit = rand(1, 50);
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
})->repeat(10);

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
