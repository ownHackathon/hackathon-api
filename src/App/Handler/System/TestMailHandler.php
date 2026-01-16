<?php declare(strict_types=1);

namespace App\Handler\System;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

readonly class TestMailHandler implements RequestHandlerInterface
{
    public function __construct(
        private Mailer $mailer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
        return new JsonResponse(['message' => 'You have a Mail'], HTTP::STATUS_CREATED);
    }
}
