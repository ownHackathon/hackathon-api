<?php declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class TopicList
{
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/Topic')
    )]
    public array $topics;

    public function __construct(array $topics)
    {
        $this->topics = [];

        foreach ($topics as $topic) {
            $this->topics[] = new Topic($topic);
        }
    }
}
