<?php declare(strict_types=1);

namespace App\Test\Middleware;

use App\Test\Mock\MockRequestHandler;
use App\Test\Mock\MockServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddlewareTest extends TestCase
{
    protected readonly ServerRequestInterface $request;
    protected readonly RequestHandlerInterface $handler;

    protected function setUp(): void
    {
        $this->request = new MockServerRequest();
        $this->handler = new MockRequestHandler();
        parent::setUp();
    }
}
