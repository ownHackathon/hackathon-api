<?php declare(strict_types=1);

namespace App\Dto\Event;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class EventListDto
{
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: EventDto::class)
    )]
    public array $events;

    /**
     * @param array<EventDto> $events
     */
    public function __construct(array $events)
    {
        $this->events = $events;
    }
}
