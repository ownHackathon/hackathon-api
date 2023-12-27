<?php declare(strict_types=1);

namespace App\Handler;

use App\Dto\EventDto;
use App\Dto\EventListDto;
use App\Entity\Event;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventListHandler implements RequestHandlerInterface
{
    #[OA\Get(path: '/api/event', summary: 'List of all events', tags: ['Event'])]
    #[OA\QueryParameter(
        name: 'order',
        description: 'The field to be sorted by<br>
                      Options: ID|OWNER|TITLE|DESCRIPTION|DURATION|STARTTIME|STATUS',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string'),
        example: 'startTime',
    )]
    #[OA\QueryParameter(
        name: 'sort',
        description: 'determines the display order of the events<br>
                      Options: ASC|DESC',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string'),
        example: 'DESC',
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: EventDto::class)),
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array<Event> $events
         */
        $events = $request->getAttribute(EventListDto::class);

        return new JsonResponse($events, HTTP::STATUS_OK);
    }
}
