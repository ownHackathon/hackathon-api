<?php declare(strict_types=1);

namespace App\Workspace\Middleware;

use App\Workspace\Domain\Message\WorkspaceLogMessage;
use App\Workspace\Domain\Message\WorkspaceStatusMessage;
use App\Workspace\DTO\WorkspaceRequest;
use App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use Core\Http\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly final class WorkspaceCreateValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private WorkspaceCreateValidator $validator,
    ) {
    }

    #[\Override]
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
            $result = $this->validator->validate($data);

            if (!$result->valid()) {
                throw new HttpInvalidArgumentException(
                    WorkspaceLogMessage::INVALID_WORKSPACE_NAME,
                    WorkspaceStatusMessage::INVALID_WORKSPACE_NAME,
                    [
                        'Validator Message:' => $result->getMessages()->toArray(),
                    ],
                );
            }

            $workspaceName = WorkspaceRequest::fromArray($result->value());
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
