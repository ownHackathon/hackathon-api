<?php declare(strict_types=1);

namespace Administration\Service\EMail;

use App\Entity\Topic;

interface EMailServiceInterface
{
    public function send(Topic $topic): void;
}
