<?php declare(strict_types=1);

namespace Tests\Integration\Workspace;

use ownHackathon\App\Workspace\Infrastructure\Service\SlugService;
use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface as Http;
use PDO;
use Tests\Integration\JsonFactory;

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
            'visibility' => 700,
        ]
    )
    ->withHeader('Authorization', $account['accessToken']);

    $slug = $this->getContainer()->get(SlugService::class)->getSlugFromString($name);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
    ->and($response->getHeader('Location')[0])->toBe('/api/workspace/' . $slug)
        ->and(JsonFactory::create($response))
        ->toBeArray()
        ->toHaveKeys(['uuid', 'ownerUuid', 'name', 'slug', 'description'])
        ->toHaveSubset([
            'ownerUuid' => $account['uuid'],
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
        ])
    ->and('Workspace')->toHaveRecord(
        [
                'accountId' => $account['id'],
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
            ]
    );
});

test('Workspace creation returns the complete response contract', function () {
    $account = $this->createAndLoginUser();
    $name = 'A workspace with a slug';

    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace/', [
            'name' => $name,
            'description' => 'Description',
            'visibility' => 100,
        ])->withHeader('Authorization', $account['accessToken'])
    );
    $data = JsonFactory::create($response);

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and($response->getHeaderLine('Content-Type'))->toContain('application/json')
        ->and($response->getHeaderLine('Location'))->toBe('/api/workspace/a-workspace-with-a-slug')
        ->and($data)->toHaveKeys(['uuid', 'ownerUuid', 'name', 'slug', 'description'])
        ->and($data['uuid'])->toMatch('/^[0-9a-f-]{36}$/i')
        ->and($data['ownerUuid'])->toBe($account['uuid']);
});

test('Workspace creation persists optional fields, visibility and the authenticated owner', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', [
            'name' => 'Workspace with all fields',
            'description' => '  a description  ',
            'details' => '  private details  ',
            'visibility' => 600,
        ])->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and('Workspace')->toHaveRecord([
            'accountId' => $account['id'],
            'name' => 'Workspace with all fields',
            'description' => 'a description',
            'details' => 'private details',
            'visibility' => 600,
        ]);
});

test('Workspace name accepts the minimum and maximum length', function (string $name) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => $name, 'visibility' => 700])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED);
})->with([
    'minimum length' => 'abc',
    'maximum length' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
]);

test('Workspace name rejects values outside its length boundaries', function (string $name) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => $name, 'visibility' => 700])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
})->with([
    'too short' => 'ab',
    'too long' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
]);

test('Workspace description accepts 255 characters and rejects 256 characters', function (int $length, int $status) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', [
            'name' => 'Description boundary ' . $length,
            'description' => str_repeat('d', $length),
            'visibility' => 700,
        ])->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe($status);
})->with([
    'maximum length' => [255, Http::STATUS_CREATED],
    'too long' => [256, Http::STATUS_BAD_REQUEST],
]);

test('Workspace creation rejects a missing name', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['visibility' => 700])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});

test('Workspace creation rejects null and incorrectly typed names', function (mixed $name) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => $name, 'visibility' => 700])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
})->with([
    'null' => null,
    'array' => [['invalid']],
    'number' => 123,
]);

test('Workspace creation accepts every supported visibility value', function (int $visibility) {
    $account = $this->createAndLoginUser();
    $name = 'Visibility workspace ' . $visibility;
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', [
            'name' => $name,
            'visibility' => $visibility,
        ])->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and('Workspace')->toHaveRecord(['name' => $name, 'visibility' => $visibility]);
})->with([100, 200, 300, 400, 500, 600, 700]);

test('Workspace creation rejects invalid visibility values', function (mixed $visibility) {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', [
            'name' => 'Invalid visibility workspace',
            'visibility' => $visibility,
        ])->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
})->with([
    'below minimum' => 99,
    'above maximum' => 701,
    'zero' => 0,
    'text injection' => '700 OR 1=1',
    'array' => [['700']],
]);

test('Workspace creation defaults omitted optional fields safely', function () {
    $account = $this->createAndLoginUser();
    $response = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => 'Defaults workspace', 'visibility' => 700])
            ->withHeader('Authorization', $account['accessToken'])
    );

    expect($response->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and('Workspace')->toHaveRecord([
            'accountId' => $account['id'],
            'name' => 'Defaults workspace',
            'description' => null,
            'details' => null,
            'visibility' => 700,
        ]);
});

test('Duplicate workspace names return conflict and do not create a second row', function () {
    $firstAccount = $this->createAndLoginUser();
    $name = 'Globally unique workspace';
    $firstResponse = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => $name, 'visibility' => 700])
            ->withHeader('Authorization', $firstAccount['accessToken'])
    );
    $secondResponse = $this->app->handle(
        $this->createJsonPostRequest('/api/workspace', ['name' => $name, 'visibility' => 700])
            ->withHeader('Authorization', $firstAccount['accessToken'])
    );
    /** @var PDO $pdo */
    $pdo = $this->getContainer()->get(PDO::class);
    $count = $pdo->query("SELECT COUNT(*) FROM Workspace WHERE name = 'Globally unique workspace'")->fetchColumn();

    expect($firstResponse->getStatusCode())->toBe(Http::STATUS_CREATED)
        ->and($secondResponse->getStatusCode())->toBe(Http::STATUS_CONFLICT)
        ->and(JsonFactory::create($secondResponse))->toHaveSubset([
            'statusCode' => Http::STATUS_CONFLICT,
            'message' => 'workspace name already in use',
        ])
        ->and($count)->toBe(1);
});

test('Workspace creation rejects unsupported methods', function (string $method) {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest('/api/workspace', ['name' => 'Method workspace'])
        ->withMethod($method)
        ->withHeader('Authorization', $account['accessToken']);

    expect($this->app->handle($request)->getStatusCode())->toBe(Http::STATUS_METHOD_NOT_ALLOWED);
})->with(['GET', 'PATCH', 'DELETE']);

test('Authorization missed', function () {
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => Faker::create()->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{10,30}'),
            'description' => Faker::create()->text(50),
            'visibility' => 700,
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
            'visibility' => 700,
        ]
    )->withHeader('Authorization', $account['refreshToken']);

    $response = $this->app->handle($request);
    expect($response->getStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('Workspace name invalid', function () {
    $account = $this->createAndLoginUser();
    $request = $this->createJsonPostRequest(
        '/api/workspace',
        [
            'name' => '12',
            'description' => Faker::create()->text(50),
            'visibility' => 700,
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
            'name' => Faker::create()->word() . ' ' . 'ä',
            'description' => Faker::create()->text(50),
            'visibility' => 700,
        ]
    )->withHeader('Authorization', $account['accessToken']);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST);
});
