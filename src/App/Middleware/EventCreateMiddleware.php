<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Event;
use App\Model\User;
use App\Service\EventService;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventCreateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService,
        private ClassMethodsHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            return $handler->handle($request);
        }

        /** @var User $user */
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        $data = $request->getParsedBody();
        $data['userId'] = $user->getId();
        $event = $this->hydrator->hydrate($data, new Event());

        if (!$this->eventService->create($event)) {
            $validationMessages = [
                'topic' => [
                    'message' => 'Thema bereits vorhanden',
                ],
            ];

            return $handler->handle($request->withAttribute('validationMessages', $validationMessages));
        }

        return $handler->handle($request);
    }
}
