<?php declare(strict_types=1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface;
use Helmich\JsonAssert\JsonAssertions;
use Laminas\Diactoros\ServerRequest;

use function json_decode;
use function time;

class PingTest extends FunctionalTestCase
{
    use JsonAssertions;

    public function testDispatchRequest(): void
    {
        $request = new ServerRequest(uri: '/api/ping', method: 'GET');

        $result = $this->app->dispatchRequest($request);
        $content = json_decode($result->getBody()->getContents(), true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $result->getStatusCode());
        $this->assertJsonValueMatches($content, '$.ack', self::greaterThanOrEqual(time()));
    }
}
