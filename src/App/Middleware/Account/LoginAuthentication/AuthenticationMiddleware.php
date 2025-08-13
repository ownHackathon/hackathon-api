<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Type\Email;

readonly class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationService $service,
        private AccountRepositoryInterface $repository,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $email = new Email($data['email']);

        $account = $this->repository->findByEmail($email);

        if (!($account instanceof AccountInterface)) {
            $this->logger->notice('An account with the specified email address was not found.', [
                'E-Mail:' => $email->toString(),
            ]);

            $message = HttpFailureMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        if (!$this->service->isPasswordMatch($data['password'], $account->getPasswordHash())) {
            $this->logger->notice('Incorrect password', [
                'E-Mail:' => $email->toString(),
            ]);

            $message = HttpFailureMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
