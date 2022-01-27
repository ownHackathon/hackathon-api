<?php declare(strict_types=1);

namespace Administration;

use App\Handler\IndexHandler;
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
                TemplateRendererInterface::class,
            ],
            Middleware\FrontLoaderMiddleware::class => [
                IndexHandler::class,
            ],
        ];
    }
}
