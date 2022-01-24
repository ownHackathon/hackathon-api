<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;

use function array_rand;

class TopicVoterService
{
    /** @param array<Topic> $topics */
    public function getSelectRandomlyTopic(array $topics): Topic
    {
        $randomKey = array_rand($topics);

        return $topics[$randomKey];
    }
}
