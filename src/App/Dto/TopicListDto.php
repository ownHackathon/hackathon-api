<?php declare(strict_types=1);

namespace App\Dto;

use App\Entity\Topic;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class TopicListDto
{
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: TopicCreateResponseDto::class)
    )]
    public array $topics;

    /**
     * @param array<Topic> $topics
     */
    public function __construct(array $topics)
    {
        $topicList = [];

        foreach ($topics as $topic) {
            $topicList[] = new TopicCreateResponseDto($topic);
        }

        $this->topics = $topicList;
    }
}
