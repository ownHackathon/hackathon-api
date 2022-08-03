<?php

namespace App\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventParticipantUnsubsribeHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $participantRemoveStatus = $request->getAttribute('participantRemoveStatus');

        if (!$participantRemoveStatus) {
            return new JsonResponse(['Status' => 'Benutzer konnte der Teilnehmerliste nicht entfernt werden'], HTTP::STATUS_METHOD_NOT_ALLOWED);
        }
        return new JsonResponse(['Status' => 'OK'], HTTP::STATUS_OK);
    }
}
