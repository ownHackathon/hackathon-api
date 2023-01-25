<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Hydrator\ReflectionHydrator;
use App\Model\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiMeHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    #[OA\Get(
        path: '/api/me',
        responses: [
            new OA\Response(
                response: HTTP::STATUS_OK,
                description: 'Success.',
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        if (!$user instanceof User) {
            return new JsonResponse([], HTTP::STATUS_OK);
        }

        $data = $this->hydrator->extract($user);

        unset($data['id'], $data['password'], $data['email']);

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
