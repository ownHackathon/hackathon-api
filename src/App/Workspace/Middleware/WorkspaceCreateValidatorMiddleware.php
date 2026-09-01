<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Middleware;

use ownHackathon\Core\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceStatusMessage;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;
use ownHackathon\App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly class WorkspaceCreateValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private WorkspaceCreateValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpInvalidArgumentException(
                WorkspaceLogMessage::INVALID_WORKSPACE_NAME,
                WorkspaceStatusMessage::INVALID_WORKSPACE_NAME,
            );
        }

        try {
            $this->validator->setData($data);

            if (!$this->validator->isValid()) {
                throw new HttpInvalidArgumentException(
                    WorkspaceLogMessage::INVALID_WORKSPACE_NAME,
                    WorkspaceStatusMessage::INVALID_WORKSPACE_NAME,
                    [
                        'Validator Message:' => $this->validator->getMessages(),
                    ]
                );
            }

            $workspaceName = WorkspaceRequest::fromArray($this->validator->getValues());
        } catch (HttpInvalidArgumentException $e) {
            throw $e;
        } catch (Throwable) {
            throw new HttpInvalidArgumentException(
                WorkspaceLogMessage::INVALID_WORKSPACE_NAME,
                WorkspaceStatusMessage::INVALID_WORKSPACE_NAME,
            );
        }

        return $handler->handle($request->withAttribute(WorkspaceRequest::class, $workspaceName));
    }
}
