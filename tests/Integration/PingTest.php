<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as Http;
use Psr\Http\Message\ResponseInterface;

test('GET /api/ping returns successful json response', function () {
    // 1. Request erstellen (über unsere Helper im TestCase)
    $request = $this->createGetRequest('/api/ping');

    // 2. Request an die App übergeben (.handle() statt .run())
    $response = $this->app->handle($request);

    // 3. Status Code prüfen
    expect($response->getStatusCode())->toBe(Http::STATUS_OK);

    // 4. Body prüfen
    $body = (string)$response->getBody();
    $data = json_decode($body, true);

    expect($data)
        ->toBeArray()
        ->toHaveKey('message', 'pong')
        ->toHaveKey('ack');
});
