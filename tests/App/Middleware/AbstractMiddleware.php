<?php declare(strict_types=1);

namespace App\Test\Middleware;

use App\Hydrator\ReflectionHydrator;
use App\Test\Mock\MockRequestHandler;
use App\Test\Mock\MockServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddleware extends TestCase
{
    protected ServerRequestInterface $request;
    protected RequestHandlerInterface $handler;
    protected ReflectionHydrator $hydrator;

    protected function setUp(): void
    {
        $this->request = new MockServerRequest();
        $this->handler = new MockRequestHandler();
        $this->hydrator = new ReflectionHydrator();
        parent::setUp();
    }
}
