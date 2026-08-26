<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace;

use Envms\FluentPDO\Query;
use ownHackathon\Core\Shared\Infrastructure\Service\SlugService;
use ownHackathon\Core\Shared\Middleware\FluentTransactionMiddleware;
use ownHackathon\Core\Shared\Utils\UuidFactoryInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\Middleware\RequireLoginMiddleware;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Helper\UrlHelper;
use ownHackathon\App\Shared\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\App\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\App\Shared\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\App\Shared\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\App\Shared\Infrastructure\Service\WorkspaceCreatorInterface;
use ownHackathon\App\Shared\Middleware\PaginationMiddleware;
use ownHackathon\App\Shared\Validator\Input\VisibilityInput;
use ownHackathon\App\Workspace\Handler\ListOwnWorkspacesHandler;
use ownHackathon\App\Workspace\Handler\WorkspaceCreateHandler;
use ownHackathon\App\Workspace\Handler\WorkspaceHandler;
use ownHackathon\App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use ownHackathon\App\Workspace\Infrastructure\Service\PaginationService;
use ownHackathon\App\Workspace\Infrastructure\Service\WorkspaceCreator;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use ownHackathon\App\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'routes' => $this->getRoutes(),
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getRoutes(): array
    {
        return [
            [
                'path' => '/api/workspace[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    RequireLoginMiddleware::class,
                    WorkspaceCreateValidatorMiddleware::class,
                    FluentTransactionMiddleware::class,
                    WorkspaceCreateHandler::class,
                ],
                'name' => 'api_workspace_create',
            ],
            [
                'path' => '/api/me/workspaces[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    RequireLoginMiddleware::class,
                    PaginationMiddleware::class,
                    ListOwnWorkspacesHandler::class,
                ],
                'name' => 'api_workspace_list_own_workspaces',
            ],
            [
                'path' => '/api/workspace/{slug:[a-zA-Z0-9\-]+}[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    RequireLoginMiddleware::class,
                    WorkspaceHandler::class,
                ],
                'name' => 'api_workspace_detail',
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                WorkspaceHydratorInterface::class => WorkspaceHydrator::class,
                WorkspaceRepositoryInterface::class => WorkspaceRepository::class,
                WorkspaceStoreInterface::class => WorkspaceTable::class,
                WorkspaceCreatorInterface::class => WorkspaceCreator::class,
            ],
            'invokables' => [
                WorkspaceNameInput::class,
                WorkspaceDescriptionInput::class,
                WorkspaceDetailsInput::class,
            ],
            'factories' => [
                WorkspaceHydrator::class => ConfigAbstractFactory::class,
                WorkspaceRepository::class => ConfigAbstractFactory::class,
                WorkspaceTable::class => ConfigAbstractFactory::class,
                WorkspaceCreateValidator::class => ConfigAbstractFactory::class,
                WorkspaceCreateValidatorMiddleware::class => ConfigAbstractFactory::class,
                WorkspaceCreator::class => ConfigAbstractFactory::class,
                WorkspaceCreateHandler::class => ConfigAbstractFactory::class,
                ListOwnWorkspacesHandler::class => ConfigAbstractFactory::class,
                PaginationService::class => ConfigAbstractFactory::class,
                WorkspaceHandler::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            WorkspaceHydrator::class => [
                UuidFactoryInterface::class,
            ],
            WorkspaceRepository::class => [
                WorkspaceStoreInterface::class,
                WorkspaceHydratorInterface::class,
            ],
            WorkspaceTable::class => [
                Query::class,
            ],
            WorkspaceCreateValidator::class => [
                WorkspaceNameInput::class,
                WorkspaceDescriptionInput::class,
                WorkspaceDetailsInput::class,
                VisibilityInput::class,
            ],
            WorkspaceCreateValidatorMiddleware::class => [
                WorkspaceCreateValidator::class,
            ],
            WorkspaceCreator::class => [
                WorkspaceRepositoryInterface::class,
                SlugService::class,
                UuidFactoryInterface::class,
            ],
            WorkspaceCreateHandler::class => [
                WorkspaceCreatorInterface::class,
                UrlHelper::class,
            ],
            ListOwnWorkspacesHandler::class => [
                WorkspaceRepositoryInterface::class,
                PaginationService::class,
            ],
            PaginationService::class => [
                WorkspaceRepositoryInterface::class,
                PaginationTotalPages::class,
            ],
            WorkspaceHandler::class => [
                WorkspaceRepositoryInterface::class,
                AccountRepositoryInterface::class,
            ]
        ];
    }
}
