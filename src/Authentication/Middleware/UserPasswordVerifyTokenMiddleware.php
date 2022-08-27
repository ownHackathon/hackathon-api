<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserPasswordVerifyTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getAttribute('token');

        $user = $this->userService->findByToken($token);

        if (!$user instanceof User) {
            return new JsonResponse(
                ['message' => 'Password cannot be changed due to invalid token'],
                HTTP::STATUS_BAD_REQUEST
            );
        }

        return $handler->handle($request->withAttribute(User::class, $user));
    }
}
