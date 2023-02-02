<?php declare(strict_types=1);

namespace App\DTO;

class TopicList
{
    public array $topics;

    public function __construct(array $topics)
    {
        $this->topics = [];

        foreach ($topics as $topic) {
            $this->topics[] = new Topic($topic);
        }
    }
}
