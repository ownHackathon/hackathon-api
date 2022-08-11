<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserRegisterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ClassMethodsHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            return $handler->handle($request);
        }

        $data = $request->getParsedBody();
        $data['name'] = $data['username'];
        $user = $this->hydrator->hydrate($data, new User());

        if (!$this->userService->create($user)) {
            $validationMessages = [
                'userName' => [
                    'message' => 'Invalid registration data',
                ],
            ];

            return $handler->handle($request->withAttribute('validationMessages', $validationMessages));
        }

        return $handler->handle($request);
    }
}
