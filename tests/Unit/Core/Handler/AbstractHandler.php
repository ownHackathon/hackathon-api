<?php declare(strict_types=1);

namespace Test\Unit\Core\Handler;

use Core\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Test\Unit\Mock\MockServerRequest;

abstract class AbstractHandler extends TestCase
{
    protected ServerRequestInterface $request;
    protected ReflectionHydrator $hydrator;

    protected function setUp(): void
    {
        $this->request = new MockServerRequest();
        $this->hydrator = new ReflectionHydrator();
        parent::setUp();
    }
}
