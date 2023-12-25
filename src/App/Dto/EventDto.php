<?php declare(strict_types=1);

namespace App\Dto;

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

    #[OA\Property(description: 'A detailed description of what this event is about', type: 'string')]
    public string $description;

    #[OA\Property(description: 'Duration of the event', type: 'integer', format: 'in Days')]
    public int $duration;

    #[OA\Property(description: 'The time from which the event starts', type: 'string', format: 'Y-M-D hh:mm')]
    public string $startTime;

    #[OA\Property(description: 'Which status', type: 'integer')]
    public int $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->owner = $data['owner'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->duration = $data['duration'];
        $this->startTime = $data['startTime'];
        $this->status = $data['status'];
    }
}
