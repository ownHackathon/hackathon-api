<?php declare(strict_types=1);

namespace Core\Middleware;

use App\Service\User\UserService;
use Core\Entity\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UserPasswordVerifyTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getAttribute('token');

        /** ToDo implements Token */
        $user = $this->userService->findById($token);

        if (!$user instanceof User) {
            return new JsonResponse(
                ['message' => 'Password cannot be changed due to invalid token'],
                HTTP::STATUS_BAD_REQUEST
            );
        }

        return $handler->handle($request->withAttribute(User::class, $user));
    }
}
