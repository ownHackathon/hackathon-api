<?php declare(strict_types=1);

namespace Core\Middleware;

use App\Service\User\UserService;
use Core\Entity\User;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function password_hash;

use const PASSWORD_BCRYPT;

readonly class UserPasswordChangeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::class);

        $data = $request->getParsedBody();

        /** ToDo implements Token support */
        $user = $user->with(['password' => password_hash($data['password'], PASSWORD_BCRYPT)]);

        if (!$this->userService->update($user)) {
            return new JsonResponse(['Password could not be changed'], Http::STATUS_BAD_REQUEST);
        }

        return $handler->handle($request);
    }
}
