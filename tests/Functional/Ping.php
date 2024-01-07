<?php declare(strict_types=1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\ServerRequest;

class Ping extends FunctionalTestCase
{
    public function testPing(): void
    {
        $request = new ServerRequest(
            uri: '/api/ping',
            method: 'GET'
        );

        $response = $this->app->handle($request);

        $this->assertEquals(HTTP::STATUS_OK, $response->getStatusCode());
    }
}
