<?php declare(strict_types=1);

namespace App\Handler;

use App\Hydrator\ReflectionHydrator;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventListHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $events = $request->getAttribute('events') ?? [];

        $data = [];

        foreach ($events as $event) {
            $data[] = $this->hydrator->extract($event);
        }

        return new JsonResponse($data);
    }
}
