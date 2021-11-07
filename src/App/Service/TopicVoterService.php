<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;

use function array_rand;
use function mt_srand;

class TopicVoterService
{
    /**@param Topic[] $topics */
    public function getSelectRandomlyTopic(array $topics): Topic
    {
        mt_srand((int)microtime());
        $randomKey = array_rand($topics);

        return $topics[$randomKey];
    }
}
