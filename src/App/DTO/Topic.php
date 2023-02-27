<?php declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class Topic
{
    #[OA\Property(
        description: 'unique one-time identification number of the topic',
        type: 'string',
        example: 'bec152fa163d406696633263761cbfbd'
    )]
    public string $uuid;

    #[OA\Property(
        description: 'The general designation of the topic',
        type: 'string',
        example: 'build a single page'
    )]
    public string $topic;

    #[OA\Property(
        description: 'The exact description of the topic with task etc.',
        type: 'string',
        example: 'Create a page with a single page'
    )]
    public string $description;

    public function __construct(array $topic)
    {
        $this->uuid = $topic['uuid'] ?? '';
        $this->topic = $topic['topic'] ?? '';
        $this->description = $topic['description'] ?? '';
    }
}
