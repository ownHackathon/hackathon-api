<?php declare(strict_types=1);

namespace App\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddlewareTest extends TestCase
{
    protected ServerRequestInterface $request;
    protected RequestHandlerInterface $handler;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
        parent::setUp();
    }
}
