<?php declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class TopicListDto
{
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: TopicDto::class)
    )]
    public array $topics;

    public function __construct(array $topics)
    {
        $this->topics = [];

        foreach ($topics as $topic) {
            $this->topics[] = new TopicDto($topic);
        }
    }
}
