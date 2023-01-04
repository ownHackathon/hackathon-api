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

use function sprintf;

class UserPasswordForgottonHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly Mailer $mailer,
        private readonly string $mailSender,
        private readonly string $projectUri,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::class);

        $email = (new Email())
            ->from($this->mailSender)
            ->to($user->getEmail())
            ->subject('Password forgotton')
            ->text(
                sprintf(
                    'Follow the link to change your password: %s/user/password/%s',
                    $this->projectUri,
                    $user->getToken(),
                )
            );

        $this->mailer->send($email);

        return new JsonResponse(['message' => 'Email was created and sent'], HTTP::STATUS_OK);
    }
}
