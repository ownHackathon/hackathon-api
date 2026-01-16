<?php declare(strict_types=1);

namespace ownHackathon\Core\Listener;

use Laminas\Diactoros\Response\JsonResponse;
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
    ): ResponseInterface {
        if ($response instanceof JsonResponse) {
            return $response;
        }
        $serverParams = $request->getServerParams();

        $this->logger->critical('Critical Error', [
            'Host' => $request->getServerParams()['REMOTE_ADDR'],
            'user-agent' => $serverParams['HTTP_USER_AGENT'],
            'Code' => $error->getCode(),
            'Message' => $error->getMessage(),
            'Exception' => $error,
        ]);

        return $response;
    }
}
