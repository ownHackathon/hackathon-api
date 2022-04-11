<?php declare(strict_types=1);

namespace Administration;

use App\Handler\IndexHandler;
use App\Service\UserService;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Template\TemplateRendererInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [

            ],
            'factories' => [
                Handler\IndexHandler::class => ConfigAbstractFactory::class,
                Handler\TemplateHandler::class => ConfigAbstractFactory::class,
                Middleware\FrontLoaderMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UpdateLastUserActionTimeMiddleware::class => ConfigAbstractFactory::class,
                Service\TemplateService::class => Service\TemplateServiceFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\IndexHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\TemplateHandler::class => [
                Service\TemplateService::class,
            ],
            Middleware\FrontLoaderMiddleware::class => [
                IndexHandler::class,
            ],
            Middleware\UpdateLastUserActionTimeMiddleware::class => [
                UserService::class,
            ],
        ];
    }
}
