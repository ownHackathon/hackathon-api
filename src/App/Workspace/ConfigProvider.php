<?php declare(strict_types=1);

namespace App\Workspace;

use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Middleware\RequireLoginMiddleware;
use App\Policy\Domain\VisibilityPolicyInterface;
use App\Workspace\Application\Port\WorkspaceCreatorInterface;
use App\Workspace\Application\Port\WorkspaceLoggerInterface;
use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use App\Workspace\Handler\ListOwnWorkspacesHandler;
use App\Workspace\Handler\WorkspaceCreateHandler;
use App\Workspace\Handler\WorkspaceHandler;
use App\Workspace\Infrastructure\Factory\WorkspaceLoggerFactory;
use App\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use App\Workspace\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use App\Workspace\Infrastructure\Service\PaginationService;
use App\Workspace\Infrastructure\Service\PaginationTotalPages;
use App\Workspace\Infrastructure\Service\SlugService;
use App\Workspace\Infrastructure\Service\WorkspaceCreator;
use App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use App\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;
use Core\Http\Middleware\PaginationMiddleware;
use Core\Persistence\Middleware\FluentTransactionMiddleware;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use Envms\FluentPDO\Query;
use Laminas\InputFilter\Factory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;

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
            'factories' => [
                WorkspaceLoggerInterface::class => WorkspaceLoggerFactory::class,
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
                PaginationTotalPages::class => InvokableFactory::class,
                SlugService::class => InvokableFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            WorkspaceHydrator::class => [
                UuidFactoryInterface::class,
                WorkspaceLoggerInterface::class,
            ],
            WorkspaceRepository::class => [
                WorkspaceStoreInterface::class,
                WorkspaceHydratorInterface::class,
            ],
            WorkspaceTable::class => [
                Query::class,
            ],
            WorkspaceCreateValidator::class => [
                Factory::class,
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
