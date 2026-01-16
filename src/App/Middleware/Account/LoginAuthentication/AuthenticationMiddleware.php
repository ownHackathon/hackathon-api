<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Type\Email;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function array_key_exists;

readonly class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationService $service,
        private AccountRepositoryInterface $accountRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!array_key_exists('email', $data)) {
            $this->logger->notice('Required argument was not passed to the route. Argument => email');

            $message = HttpFailureMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        $email = new Email($data['email']);

        $account = $this->accountRepository->findByEmail($email);

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

        $account = $account->with(lastActionAt: new DateTimeImmutable());

        $this->accountRepository->update($account);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
