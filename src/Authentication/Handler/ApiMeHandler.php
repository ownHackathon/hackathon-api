<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Enum\UserRole;
use App\Entity\User;
use Authentication\DTO\ApiMe;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiMeHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/api/me',
        tags: ['User Control'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_OK,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiMe')
            ),
            new OA\Response(
                response: HTTP::STATUS_UNAUTHORIZED,
                description: 'Incorrect authorization or expired'
            ),
        ],
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        if (!$user instanceof User) {
            return new JsonResponse([], HTTP::STATUS_OK);
        }

        $data = $this->extractResponseData($user);

        return new JsonResponse($data, HTTP::STATUS_OK);
    }

    private function extractResponseData(User $user): ApiMe
    {
        $apiMe = new ApiMe();
        $apiMe->uuid = $user->getUuid();
        $apiMe->name = $user->getName();
        $apiMe->role = UserRole::from($user->getRoleId())->getRoleName();

        return $apiMe;
    }
}
