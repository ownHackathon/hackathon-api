<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function password_hash;

class UserPasswordChangeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::class);

        $data = $request->getParsedBody();

        $user->setToken(null);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

        if (!$this->userService->update($user)) {
            return new JsonResponse(['Password could not be changed'], Http::STATUS_BAD_REQUEST);
        }

        return $handler->handle($request);
    }
}
