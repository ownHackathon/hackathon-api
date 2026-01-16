<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\ClientIdentificationData;
use ownHackathon\App\Middleware\Token\GenerateRefreshTokenMiddleware;
use ownHackathon\UnitTest\Mock\Service\MockRefreshTokenService;

class GenerateRefreshTokenMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new GenerateRefreshTokenMiddleware(
            new MockRefreshTokenService()
        );
    }

    public function testCanGenerateRefreshToken(): void
    {
        $data = ClientIdentification::create(
            ClientIdentificationData::create(null, 'defaul'),
            '1'
        );

        $request = $this->request->withAttribute(ClientIdentification::class, $data);
        $response = $this->middleware->process($request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }
}
