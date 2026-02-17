<?php declare(strict_types=1);

namespace ownHackathon\Workspace;

use Envms\FluentPDO\Query;
use Exdrals\Core\Shared\Infrastructure\Service\SlugService;
use Exdrals\Core\Shared\Middleware\FluentTransactionMiddleware;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Exdrals\Identity\Middleware\RequireLoginMiddleware;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;
use ownHackathon\Shared\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Shared\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\Shared\Infrastructure\Service\WorkspaceCreatorInterface;
use ownHackathon\Shared\Middleware\PaginationMiddleware;
use ownHackathon\Workspace\Handler\ListOwnWorkspacesHandler;
use ownHackathon\Workspace\Handler\WorkspaceCreateHandler;
use ownHackathon\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use ownHackathon\Workspace\Infrastructure\Service\PaginationService;
use ownHackathon\Workspace\Infrastructure\Service\WorkspaceCreator;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use ownHackathon\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use ownHackathon\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;

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
                'path' => '/api/workspace/{slug}',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    RequireLoginMiddleware::class,
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
            ],
            'factories' => [
                WorkspaceHydrator::class => ConfigAbstractFactory::class,
                WorkspaceRepository::class => ConfigAbstractFactory::class,
                WorkspaceTable::class => ConfigAbstractFactory::class,
                WorkspaceNameInput::class => InvokableFactory::class,
                WorkspaceDescriptionInput::class => InvokableFactory::class,
                WorkspaceCreateValidator::class => ConfigAbstractFactory::class,
                WorkspaceCreateValidatorMiddleware::class => ConfigAbstractFactory::class,
                WorkspaceCreator::class => ConfigAbstractFactory::class,
                WorkspaceCreateHandler::class => ConfigAbstractFactory::class,
                ListOwnWorkspacesHandler::class => ConfigAbstractFactory::class,
                PaginationService::class => ConfigAbstractFactory::class,
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
            ],
            WorkspaceTable::class => [
                Query::class,
                WorkspaceHydratorInterface::class,
            ],
            WorkspaceCreateValidator::class => [
                WorkspaceNameInput::class,
                WorkspaceDescriptionInput::class,
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
        ];
    }
}
