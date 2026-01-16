<?php declare(strict_types=1);

namespace Core\Handler;

use Core\Entity\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

use function sprintf;

readonly class UserPasswordForgottonHandler implements RequestHandlerInterface
{
    public function __construct(
        private Mailer $mailer,
        private string $mailSender,
        private string $projectUri,
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
            ->to($user->email)
            ->subject('Password forgotton')
            ->text(
                sprintf(
                    'Follow the link to change your password: %s/user/password/%s',
                    $this->projectUri,
                    $user->name, /** ToDo implements Token support */
                )
            );

        $this->mailer->send($email);

        return new JsonResponse(['message' => 'Email was created and sent'], HTTP::STATUS_OK);
    }
}
