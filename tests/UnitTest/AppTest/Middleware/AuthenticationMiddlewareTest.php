<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Exdrals\Account\Identity\Middleware\Account\LoginAuthentication\AuthenticationMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\MiddlewareInterface;
use Shared\Domain\Exception\HttpUnauthorizedException;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Repository\MockAccountRepository;
use UnitTest\Mock\Service\MockAuthenticationService;

class AuthenticationMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new AuthenticationMiddleware(
            new MockAuthenticationService(),
            new MockAccountRepository(),
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

        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testCanNotFoundAccountWithEmail(): void
    {
        $bodyData = [
            'email' => Account::EMAIL_INVALID,
            'password' => Account::PASSWORD,
        ];

        $request = $this->request->withParsedBody($bodyData);

        $this->expectException(HttpUnauthorizedException::class);
        $this->middleware->process($request, $this->handler);
    }

    public function testRequestWithInvalidPassword(): void
    {
        $bodyData = [
            'email' => Account::EMAIL,
            'password' => Account::PASSWORD_INVALID,
        ];

        $request = $this->request->withParsedBody($bodyData);

        $this->expectException(HttpUnauthorizedException::class);
        $this->middleware->process($request, $this->handler);
    }
}
