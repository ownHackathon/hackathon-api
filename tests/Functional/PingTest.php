<?php declare(strict_types=1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface;
use Helmich\JsonAssert\JsonAssertions;
use Laminas\Diactoros\ServerRequest;

use function time;

class PingTest extends FunctionalTestCase
{
    use JsonAssertions;
    use JsonRequestHelper;

    public function testDispatchRequest(): void
    {
        $request = new ServerRequest(uri: '/api/ping', method: 'GET');

        $response = $this->app->dispatchRequest($request);

        self::assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        self::assertJsonValueMatches(
            self::getContentAsJson($response),
            '$.ack',
            self::greaterThanOrEqual(time())
        );
    }
}
