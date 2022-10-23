<?php declare(strict_types=1);

namespace App\Test\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddlewareTest extends TestCase
{
    protected ServerRequestInterface&MockObject $request;
    protected RequestHandlerInterface&MockObject $handler;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
        parent::setUp();
    }
}
