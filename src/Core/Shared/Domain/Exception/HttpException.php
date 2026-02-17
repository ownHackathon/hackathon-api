<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Exception;

use Monolog\Level;
use RuntimeException;
use Throwable;

abstract class HttpException extends RuntimeException
{
    protected array $context = [];
    protected string $responseMessage;
    protected Level $logLevel;

    public function __construct(
        string $logMessage,
        string $responseMessage,
        array $context = [],
        Level $loglevel = Level::Notice,
        ?Throwable $previous = null
    ) {
        parent::__construct($logMessage, $this->getHttpStatusCode(), $previous);
        $this->context = $context;
        $this->responseMessage = $responseMessage;
        $this->logLevel = $loglevel;
    }

    abstract public function getHttpStatusCode(): int;

    public function getContext(): array
    {
        return $this->context;
    }

    public function getResponseMessage(): string
    {
        return $this->responseMessage;
    }

    public function getLogLevel(): Level
    {
        return $this->logLevel;
    }
}
