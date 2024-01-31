<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware;

use App\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Test\Unit\Mock\MockRequestHandler;
use Test\Unit\Mock\MockServerRequest;

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
        $this->hydrator->addStrategy(
            'topic',
            new HydratorStrategy(new ReflectionHydrator(), Topic::class)
        );
        parent::setUp();
    }
}
