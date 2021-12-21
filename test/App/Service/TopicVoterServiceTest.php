<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;
use PHPUnit\Framework\TestCase;

class TopicVoterServiceTest extends TestCase
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
