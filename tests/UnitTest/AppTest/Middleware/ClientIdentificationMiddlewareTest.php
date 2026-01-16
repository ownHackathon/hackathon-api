<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use UnitTest\Mock\Service\MockClientIdentificationService;

class ClientIdentificationMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new ClientIdentificationMiddleware(
            new MockClientIdentificationService(),
        );
    }

    public function testGenerateClientIdentification(): void
    {
        $request = $this->request->withHeader('x-ident', '1')
            ->withHeader('user-agent', 'Test Browser Agent');

        $response = $this->middleware->process($request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    // ToDo Create test for error cases
}
