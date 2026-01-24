<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\ClientIdentification;

use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Client\ClientIdentificationData;
use Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ClientIdentificationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ClientIdentificationService $clientIdentification
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $clientIdent = $request->getHeaderLine('x-ident');
        $userAgent = $request->getHeaderLine('user-agent');

        $clientIdentificationData = ClientIdentificationData::create($clientIdent, $userAgent);
        $identificationHash = $this->clientIdentification->getClientIdentificationHash($clientIdentificationData);
        $clientIdentification = ClientIdentification::create($clientIdentificationData, $identificationHash);

        return $handler->handle($request->withAttribute(ClientIdentification::class, $clientIdentification));
    }
}
