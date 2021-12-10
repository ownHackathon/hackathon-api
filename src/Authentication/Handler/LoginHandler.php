<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Model\User;
use Authentication\Service\JwtTokenGeneratorTrait;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Session\SessionInterface;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements RequestHandlerInterface
{
    use JwtTokenGeneratorTrait;

    public function __construct(
        private TemplateRendererInterface $template,
        private string $tokenSecret,
        private int $tokenDuration
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);
        $data['validationMessages'] = $request->getAttribute('validationMessages');

        if ($user instanceof User) {
            $token = $this->generateToken(
                $user->getId(),
                $user->getName(),
                $this->tokenSecret,
                $this->tokenDuration
            );

            /** @var SessionInterface $session */
            $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
            $session->set('token', $token);

            return new RedirectResponse('/', 303);
        }

        $postData = $request->getParsedBody();
        $data['userName'] = $postData['userName'] ?? null;

        return new HtmlResponse($this->template->render('app::loginbox', $data));
    }
}
