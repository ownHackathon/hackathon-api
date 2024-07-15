<?php declare(strict_types=1);

namespace App\Handler\Event;

use App\Dto\Event\EventDto;
use App\Dto\Event\EventListDto;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventListHandler implements RequestHandlerInterface
{
    #[OA\Get(path: '/api/event', summary: 'List of all events', tags: ['Event'], deprecated: true)]
    #[OA\QueryParameter(
        name: 'order',
        description: 'The field to be sorted by<br>
                      Options: ID|OWNER|TITLE|DESCRIPTION|DURATION|STARTEDAT|STATUS',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string'),
        example: 'startedAt',
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
        /** @var EventListDto $events */
        $events = $request->getAttribute(EventListDto::class);

        return new JsonResponse($events, HTTP::STATUS_OK);
    }
}
