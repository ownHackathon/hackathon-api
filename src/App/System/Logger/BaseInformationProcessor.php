<?php declare(strict_types=1);

namespace App\System\Logger;

use Laminas\Log\Processor\ProcessorInterface;

use function filter_input;

use function filter_input_array;

use const INPUT_GET;
use const INPUT_SERVER;

class BaseInformationProcessor implements ProcessorInterface
{
    public function process(array $event): array
    {
        if (!isset($event['extra'])) {
            $event['extra'] = [];
        }

        $event['extra']['Host'] = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $event['extra']['URI'] = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $event['extra']['Method'] = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $event['extra']['Redirect'] = filter_input(INPUT_SERVER, 'REDIRECT_URL');
        $event['extra']['Query'] = filter_input_array(INPUT_GET);

        return $event;
    }
}
