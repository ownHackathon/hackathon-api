<?php declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Dto\Core\HttpStatusCodeMessage;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserRegisterSubmitHandler implements RequestHandlerInterface
{
    #[OA\Post(path: '/api/user/register', summary: 'Register a user with e-mail', tags: ['User Control'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
               new OA\Property(property: 'e-mail', title: 'E-Mail', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: 'Incorrect Request Data',
        content: new OA\JsonContent(ref: HttpStatusCodeMessage::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'Account was created'], HTTP::STATUS_OK);
    }
}
