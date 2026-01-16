<?php declare(strict_types=1);

namespace Core\Listener;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class LoggingErrorListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(
        Throwable $error,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): void {
        $serverParams = $request->getServerParams();

        $this->logger->error(
            '{Host} Code: {Code} - Message: {Message}',
            [
                'user-agent' => $serverParams['HTTP_USER_AGENT'],
                'Code' => $error->getCode(),
                'Message' => $error->getMessage(),
            ],
        );
    }
}
