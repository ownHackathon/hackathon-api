<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;

class TopicVoterServiceTest extends AbstractServiceTest
{
    public function testCanGetSelectRandomlyTopic(): void
    {
        $topics = [
            new Topic(),
            new Topic(),
        ];

        $service = new TopicVoterService();

        $topic = $service->getSelectRandomlyTopic($topics);

        $this->assertInstanceOf(Topic::class, $topic);
    }
}
