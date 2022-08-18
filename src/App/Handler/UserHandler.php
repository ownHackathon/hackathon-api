<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ClassMethodsHydrator $hydrator,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::class);

        $data = $this->hydrator->extract($user);
        unset($data['id'], $data['password'], $data['email']);

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
