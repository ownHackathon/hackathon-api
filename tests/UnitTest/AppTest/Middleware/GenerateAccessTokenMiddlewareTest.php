<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use ownHackathon\App\Middleware\Token\GenerateAccessTokenMiddleware;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\UnitTest\Mock\Entity\MockAccount;
use ownHackathon\UnitTest\Mock\Service\MockAccessTokenService;

class GenerateAccessTokenMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new GenerateAccessTokenMiddleware(
            new MockAccessTokenService()
        );
    }

    public function testCanGenerateAccessToken(): void
    {
        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, new MockAccount());
        $response = $this->middleware->process($request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }
}
