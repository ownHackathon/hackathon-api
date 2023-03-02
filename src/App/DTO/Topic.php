<?php declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class Topic extends TopicCreate
{
    #[OA\Property(
        description: 'unique one-time identification number of the topic',
        type: 'string',
        example: 'bec152fa163d406696633263761cbfbd'
    )]
    public string $uuid;

    public function __construct(array $topic)
    {
        $this->uuid = $topic['uuid'] ?? '';
        parent::__construct($topic);
    }
}
