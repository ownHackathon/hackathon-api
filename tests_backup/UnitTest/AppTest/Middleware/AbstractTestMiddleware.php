<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnitTest\Mock\MockRequestHandler;
use UnitTest\Mock\MockServerRequest;
use UnitTest\JsonRequestHelper;

abstract class AbstractTestMiddleware extends TestCase
{
    use JsonRequestHelper;
    use JsonAssertions;

    protected ServerRequestInterface $request;
    protected RequestHandlerInterface $handler;

    protected function setUp(): void
    {
        $this->request = new MockServerRequest();
        $this->handler = new MockRequestHandler();

        parent::setUp();
    }
}
