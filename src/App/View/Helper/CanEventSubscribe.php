<?php declare(strict_types=1);

namespace App\View\Helper;

use DateTime;
use Laminas\View\Helper\AbstractHelper;

class CanEventSubscribe extends AbstractHelper
{
    public function __invoke(DateTime $eventStartTime): bool
    {
        $time = new DateTime();

        if ($time < $eventStartTime) {
            return true;
        }

        return false;
    }
}
