<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Topic;
use App\Service\EMail\EMailServiceInterface;

class MockTopicCreateEMailService implements EMailServiceInterface
{
    public function send(Topic $topic): void
    {
        // TODO: Implement send() method.
    }
}
