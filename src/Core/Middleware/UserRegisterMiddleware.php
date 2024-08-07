<?php declare(strict_types=1);

namespace Core\Middleware;

use App\Enum\UserRole;
use App\Service\User\UserService;
use Core\Dto\HttpStatusCodeMessage;
use Core\Entity\User;
use Core\Exception\DuplicateEntryException;
use Core\Hydrator\ReflectionHydrator;
use DateTime;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

readonly class UserRegisterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private ReflectionHydrator $hydrator,
        private Uuid $uuid,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        $newUser = [
            'id' => 1,
            'uuid' => $this->uuid,
            'role' => UserRole::GUEST,
            'name' => '',
            'password' => '',
            'email' => $data['e-mail'],
            'registrationAt' => new DateTime(),
            'lastActionAt' => new DateTime(),
        ];

        $user = $this->hydrator->hydrate($newUser, User::class);

        try {
            !$this->userService->create($user);
        } catch (DuplicateEntryException $exception) {
            $validationMessages = [
                'email' => [
                    'message' => 'Invalid registration data',
                ],
            ];

            return new JsonResponse(
                new HttpStatusCodeMessage(
                    $exception->getCode(),
                    'Registration failed',
                    $validationMessages
                ),
                $exception->getCode()
            );
        }
        return $handler->handle($request);
    }
}
