<?php declare(strict_types=1);

namespace ownHackathon\Workspace;

use Envms\FluentPDO\Query;
use Exdrals\Shared\Infrastructure\Service\SlugService;
use Exdrals\Shared\Middleware\FluentTransactionMiddleware;
use Exdrals\Shared\Middleware\RequireLoginMiddleware;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCreatorInterface;
use ownHackathon\Shared\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Workspace\Handler\FindWorkspacesForAuthenticatedAccountHandler;
use ownHackathon\Workspace\Handler\WorkspaceCreateHandler;
use ownHackathon\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use ownHackathon\Workspace\Infrastructure\Validator\WorkspaceNameValidator;
use ownHackathon\Workspace\Middleware\WorkspaceNameValidatorMiddleware;
use ownHackathon\Workspace\Service\WorkspaceCreator;

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
                'middleware' => [
                    RequireLoginMiddleware::class,
                    WorkspaceNameValidatorMiddleware::class,
                    FluentTransactionMiddleware::class,
                    WorkspaceCreateHandler::class,
                ],
                'allowed_methods' => ['POST'],
                'name' => 'api_workspace_create',
            ],
            [
                'path' => '/api/workspace[/]',
                'middleware' => [
                    RequireLoginMiddleware::class,
                    FindWorkspacesForAuthenticatedAccountHandler::class,
                ],
                'allowed_methods' => ['GET'],
                'name' => 'api_workspace_list_for_auth_account',
            ],
            [
                'path' => '/api/workspace/{slug}',
                'middleware' => [
                    RequireLoginMiddleware::class,
                ],
                'allowed_methods' => ['GET'],
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
                WorkspaceNameValidator::class => ConfigAbstractFactory::class,

                WorkspaceNameValidatorMiddleware::class => ConfigAbstractFactory::class,
                WorkspaceCreator::class => ConfigAbstractFactory::class,
                WorkspaceCreateHandler::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            WorkspaceHydrator::class => [
                UuidFactoryInterface::class,
            ],
            WorkspaceNameValidatorMiddleware::class => [
                WorkspaceNameValidator::class,
            ],
            WorkspaceRepository::class => [
                WorkspaceStoreInterface::class,
            ],
            WorkspaceTable::class => [
                Query::class,
                WorkspaceHydratorInterface::class,
            ],
            WorkspaceNameValidator::class => [
                WorkspaceNameInput::class,
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
        ];
    }
}
