<?php declare(strict_types=1);

namespace App\Dto\Event;

use App\Enum\EventStatus;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class EventDto
{
    #[OA\Property(title: 'test', description: 'The id of the event', type: 'integer')]
    public int $id;

    #[OA\Property(description: 'user who created the event', type: 'string')]
    public string $owner;

    #[OA\Property(description: 'The title of the event', type: 'string')]
    public string $title;

    #[OA\Property(description: 'A detailed description of what this event is about', type: 'string', nullable: true)]
    public ?string $description;

    #[OA\Property(description: 'Duration of the event', type: 'integer', format: 'in Days')]
    public int $duration;

    #[OA\Property(description: 'The time from which the event starts', type: 'string', format: 'Y-M-D hh:mm')]
    public string $startTime;

    #[OA\Property(
        description: 'Which status<br> 
                      1 = coming soon <br> 
                      2 = in preparation <br>
                      3 = running <br>
                      4 = in evaluation <br>
                      5 = completed/finalized <br>
                      6 = closed <br>
                      7 = aborted <br>
                      8 = hidden',
        type: 'integer',
        enum: EventStatus::class,
    )]
    public int $status;

    public function __construct(
        int $id,
        string $owner,
        string $title,
        ?string $description,
        int $duration,
        string $startTime,
        EventStatus $status
    ) {
        $this->id = $id;
        $this->owner = $owner;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->startTime = $startTime;
        $this->status = $status->value;
    }
}
