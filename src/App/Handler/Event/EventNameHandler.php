<?php declare(strict_types=1);

namespace App\Handler\Event;

use App\Entity\Event;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventNameHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var Event $event
         */
        $event = $request->getAttribute(Event::class);

        $data = [
            'eventId' => $event->id,
        ];

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
