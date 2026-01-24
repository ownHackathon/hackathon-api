<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Logger;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

readonly class MetaDataProcessor implements ProcessorInterface
{
    public function __construct(
        private ?string $remoteAddr,
        private ?string $uri,
        private ?string $method,
        private ?string $redirect,
        private ?array $query
    ) {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['Remote'] = $this->remoteAddr;
        $record->extra['URI'] = $this->uri;
        $record->extra['Method'] = $this->method;
        $record->extra['Redirect'] = $this->redirect;
        $record->extra['Query'] = $this->query;

        return $record;
    }
}
