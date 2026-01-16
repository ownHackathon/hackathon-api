<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\UnitTest\Mock\MockRequestHandler;
use ownHackathon\UnitTest\Mock\MockServerRequest;
use ownHackathon\UnitTest\JsonRequestHelper;

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
