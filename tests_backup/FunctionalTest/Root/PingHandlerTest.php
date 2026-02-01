<?php declare(strict_types=1);

namespace FunctionalTest\Root;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Helmich\Psr7Assert\Psr7Assertions;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use FunctionalTest\AbstractFunctional;

class PingHandlerTest extends AbstractFunctional
{
    use Psr7Assertions;

    public function testPingResponse(): void
    {
        $request = new ServerRequest(
            uri: '/api/ping',
            method: 'GET',
        );
        $timestamp = time();
        $response = $this->app->handle($request);

        $this->assertSame($response->getStatusCode(), HTTP::STATUS_OK);
        $this->assertThat($response, $this->bodyMatchesJson([
            'ack' => Assert::greaterThanOrEqual($timestamp),
        ]));
    }
}
