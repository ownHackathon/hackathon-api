<?php declare(strict_types=1);

namespace App\Dto\Topic;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class TopicCreateFailureMessageDto
{
    #[OA\Property(
        properties: [
            new OA\Property(property: 'topic', type: 'string'),
        ],
        type: 'object'
    )]
    public array $topic;

    public function __construct(array $topic)
    {
        $this->topic = $topic;
    }
}
