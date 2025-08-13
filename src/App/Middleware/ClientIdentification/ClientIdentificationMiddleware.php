<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\ClientIdentification;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\ClientIdentificationData;
use ownHackathon\App\Service\ClientIdentification\ClientIdentificationService;

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
