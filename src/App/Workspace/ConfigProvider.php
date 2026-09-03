<?php declare(strict_types=1);

namespace App\Workspace;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Helper\UrlHelper;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Middleware\RequireLoginMiddleware;
use App\Policy\Domain\VisibilityPolicyInterface;
use App\Policy\Http\Validator\Input\VisibilityInput;
use App\Workspace\Application\Port\WorkspaceCreatorInterface;
use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use App\Workspace\Handler\ListOwnWorkspacesHandler;
use App\Workspace\Handler\WorkspaceCreateHandler;
use App\Workspace\Handler\WorkspaceHandler;
use App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use App\Workspace\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use App\Workspace\Infrastructure\Service\PaginationService;
use App\Workspace\Infrastructure\Service\WorkspaceCreator;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use Core\Http\Middleware\PaginationMiddleware;
use App\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;
use App\Workspace\Infrastructure\Service\PaginationTotalPages;
use App\Workspace\Infrastructure\Service\SlugService;
use Core\Observability\ChannelLoggerFactory;
use Core\Persistence\Middleware\FluentTransactionMiddleware;
use Core\SharedKernel\Utils\UuidFactoryInterface;

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
                'logger.workspace' => ChannelLoggerFactory::class,
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
                PaginationTotalPages::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                SlugService::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            WorkspaceHydrator::class => [
                UuidFactoryInterface::class,
                'logger.workspace',
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
                VisibilityPolicyInterface::class,
            ]
        ];
    }
}
