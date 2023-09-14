<?php declare(strict_types=1);

namespace App\Test\Mock\Service;

use Administration\Service\EMail\EMailServiceInterface;
use App\Entity\Topic;

class MockTopicCreateEMailService implements EMailServiceInterface
{
    public function send(Topic $topic): void
    {
        // TODO: Implement send() method.
    }
}