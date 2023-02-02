<?php declare(strict_types=1);

namespace App\Test\Handler;

use App\Hydrator\ReflectionHydrator;
use App\Test\Mock\MockServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractHandlerTest extends TestCase
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
