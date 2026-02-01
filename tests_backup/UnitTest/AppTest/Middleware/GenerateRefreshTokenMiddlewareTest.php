<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Client\ClientIdentificationData;
use Exdrals\Identity\Middleware\Token\GenerateRefreshTokenMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use UnitTest\Mock\Service\MockRefreshTokenService;

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
