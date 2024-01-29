<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Entity\Event;
use App\Entity\User;
use App\Hydrator\ReflectionHydrator;
use App\Service\Event\EventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventCreateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        $data = $request->getParsedBody();
        $data['userId'] = $user->id;

        $event = $this->hydrator->hydrate($data, Event::class);

        if (!$this->eventService->create($event)) {
            return new JsonResponse([
                'message' => 'Event already exists',
            ], HTTP::STATUS_NOT_FOUND);
        }

        return $handler->handle($request);
    }
}
