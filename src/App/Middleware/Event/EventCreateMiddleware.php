<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Hydrator\ReflectionHydrator;
use App\Model\Event;
use App\Model\User;
use App\Service\EventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventCreateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        $data = $request->getParsedBody();
        $data['userId'] = $user->getId();

        $event = $this->hydrator->hydrate($data, new Event());

        if (!$this->eventService->create($event)) {
            return new JsonResponse([
                'message' => 'Event already exists',
            ], HTTP::STATUS_NOT_FOUND);
        }

        return $handler->handle($request);
    }
}
