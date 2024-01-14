<?php declare(strict_types=1);

namespace App\Listener;

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
                'Method' => $serverParams['REQUEST_METHOD'],
                'Route' => $serverParams['REQUEST_URI'],
                'Redirect' => $serverParams['REDIRECT_URL'],
                'Host' => $serverParams['HTTP_HOST'],
                'user-agent' => $serverParams['HTTP_USER_AGENT'],
                'Query' => $request->getQueryParams(),
                'Body' => $request->getParsedBody(),
                'Code' => $error->getCode(),
                'Message' => $error->getMessage(),
            ],
        );
    }
}
