<?php declare(strict_types=1);

namespace ownHackathon\Core\Http\Exception;

use Monolog\Level;
use RuntimeException;
use Throwable;

abstract class HttpException extends RuntimeException
{
    protected array $context = [];
    protected string $responseMessage;
    protected Level $logLevel;

    abstract public function getHttpStatusCode(): int;

    public function __construct(
        string $logMessage,
        string $responseMessage,
        array $context = [],
        Level $loglevel = Level::Notice,
        ?Throwable $previous = null,
    ) {
        parent::__construct($logMessage, $this->getHttpStatusCode(), $previous);
        $this->context = $context;
        $this->responseMessage = $responseMessage;
        $this->logLevel = $loglevel;
    }

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
