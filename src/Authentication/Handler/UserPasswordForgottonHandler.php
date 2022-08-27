<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Model\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class UserPasswordForgottonHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly Mailer $mailer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute(User::class);

        $email = (new Email())
            ->from('hackathon@exdrals.de')
            ->to($user->getEmail())
            ->subject('Password forgotton')
            ->text(
                'Follow the link to change your password: https://hackathon.ddev.site/user/password/' . $user->getToken(
                )
            );

        $this->mailer->send($email);

        return new JsonResponse(['message' => 'Email was created and sent'], HTTP::STATUS_OK);
    }
}
