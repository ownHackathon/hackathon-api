<?php declare(strict_types=1);

namespace App\Dto\Topic;

use App\Entity\Topic;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class TopicCreateRequestDto
{
    #[OA\Property(
        description: 'The general designation of the topic',
        type: 'string',
    )]
    public string $topic;

    #[OA\Property(
        description: 'The exact description of the topic with task etc.',
        type: 'string',
        nullable: true
    )]
    public ?string $description;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic->topic;
        $this->description = $topic->description;
    }
}
