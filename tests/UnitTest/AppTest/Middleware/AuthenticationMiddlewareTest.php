<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use ownHackathon\App\Middleware\Account\LoginAuthentication\AuthenticationMiddleware;
use ownHackathon\FunctionalTest\Mock\NullLogger;
use ownHackathon\UnitTest\Mock\Constants\Account;
use ownHackathon\UnitTest\Mock\Repository\MockAccountRepository;
use ownHackathon\UnitTest\Mock\Service\MockAuthenticationService;

class AuthenticationMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new AuthenticationMiddleware(
            new MockAuthenticationService(),
            new MockAccountRepository(),
            new NullLogger(),
        );
    }

    public function testCanAuthenticatedAccount(): void
    {
        $bodyData = [
            'email' => Account::EMAIL,
            'password' => Account::PASSWORD,
        ];

        $request = $this->request->withParsedBody($bodyData);
        $response = $this->middleware->process($request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testCanNotFoundAccountWithEmail(): void
    {
        $bodyData = [
            'email' => Account::EMAIL_INVALID,
            'password' => Account::PASSWORD,
        ];

        $request = $this->request->withParsedBody($bodyData);
        $response = $this->middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', '400');
        $this->assertJsonValueEquals($json, '$.message', 'Invalid Data');
    }

    public function testRequestWithInvalidPassword(): void
    {
        $bodyData = [
            'email' => Account::EMAIL,
            'password' => Account::PASSWORD_INVALID,
        ];

        $request = $this->request->withParsedBody($bodyData);
        $response = $this->middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', '400');
        $this->assertJsonValueEquals($json, '$.message', 'Invalid Data');
    }
}
