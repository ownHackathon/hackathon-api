<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Dto\Core\HttpStatusCodeMessage;
use App\Entity\User;
use App\Service\User\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UserRegisterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private ClassMethodsHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        $data['email'] = $data['e-mail'];
        $user = $this->hydrator->hydrate($data, new User());

        if (!$this->userService->create($user)) {
            $validationMessages = [
                'email' => [
                    'message' => 'Invalid registration data',
                ],
            ];

            return new JsonResponse(
                new HttpStatusCodeMessage(
                    HTTP::STATUS_BAD_REQUEST,
                    'Registration failed',
                    $validationMessages
                ),
                HTTP::STATUS_BAD_REQUEST
            );
        }
        return $handler->handle($request);
    }
}
